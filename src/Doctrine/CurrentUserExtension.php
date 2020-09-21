<?php

namespace App\Doctrine;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Article;
use App\Entity\Belong;
use App\Entity\Stock;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;

class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{

    /**
     * @var Security
     */
    private $security;

    private $auth;

    /**
     * CurrentUserExtension constructor.
     * @param Security $security
     * @param AuthorizationCheckerInterface $checker
     */
    public function __construct(Security $security, AuthorizationCheckerInterface $checker )
    {
        $this->security = $security;
        $this->auth = $checker;
    }


    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass)
    {
        //Obtenir l'utilisateur connecté
        $user = $this->security->getUser();

        //si on demande des article ou des stocks alors agir sur la requête pour qu'elle tienne compte de l'utilisateur actuellement connecté"
        if (($resourceClass === Article::class || $resourceClass === Stock::class || $resourceClass === Belong::class)
            &&
            !$this->auth->isGranted('ROLE_ADMIN')
            &&
            $user instanceof User
            )
        {
            $rootAlias = $queryBuilder->getRootAliases()[0];

            if ($resourceClass == Article::class)
            {
                $queryBuilder->andWhere("$rootAlias.user = :user");
            }else if ($resourceClass === Stock::class)
            {
                $queryBuilder->andWhere("$rootAlias.user = :user");
            }else if ($resourceClass === Belong::class)
            {
                $queryBuilder->join("$rootAlias.stock", "s")
                    ->andWhere("s.user = :user");
            }

            $queryBuilder->setParameter("user", $user);
        }
    }


    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator,
                                      string $resourceClass, string $operationName = null)
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator,
                                string $resourceClass, array $identifiers, string $operationName = null, array $context = [])
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }
}