<?php

namespace App\Extension;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use Doctrine\ORM\QueryBuilder;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Article;
use Symfony\Bundle\SecurityBundle\Security;

class PublishedArticleExtension implements QueryCollectionExtensionInterface , QueryItemExtensionInterface
{

    public function __construct(private Security $security)
    {
        
    }
    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, ?Operation $operation = null, array $context = []): void
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, ?Operation $operation = null, array $context = []): void
    {
        $this->addWhere($queryBuilder, $resourceClass);

    }

    /**
     * is current use has role: ROLE_ADMIN )> select tous les article , else select where  publishedAt <= CURRENT_TIMESTAMP
     *
     * @param QueryBuilder $queryBuilder
     * @param string $resourceClass
     * @return void
     */
    private function addWhere(QueryBuilder $queryBuilder , string $resourceClass)  {
        if (Article::class !== $resourceClass || $this->security->isGranted('ROLE_zADMIN')){
            return ; //sortie
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere(sprintf('%s.publishedAt <= CURRENT_TIMESTAMP()', $rootAlias));

    }

}