<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\Database\Expression\QueryExpression;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;


/**
 * Articles Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\CommentsTable&\Cake\ORM\Association\HasMany $Comments
 * @property \App\Model\Table\FavoritesTable&\Cake\ORM\Association\HasMany $Favorites
 * @property \App\Model\Table\TagsTable&\Cake\ORM\Association\BelongsToMany $Tags
 *
 * @method \App\Model\Entity\Article get($primaryKey, $options = [])
 * @method \App\Model\Entity\Article newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Article[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Article|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Article saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Article patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Article[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Article findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ArticlesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('articles');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->addBehavior('CounterCache', [
            'Users' => [
                'article_count' => [
                    'finder' => 'published'
                ]
            ],
        ]);

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Comments', [
            'dependent' => true,
            'foreignKey' => 'article_id',
        ]);
        $this->hasMany('Favorites', [
            'dependent' => true,
            'foreignKey' => 'article_id',
        ]);
        $this->belongsToMany('Tags', [
            'dependent' => true,
            'foreignKey' => 'article_id',
            'targetForeignKey' => 'tag_id',
            'joinTable' => 'articles_tags',
        ]);
    }

    public function findPublished(Query $query): Query
    {
        return $query->where(['published' => 1]);
    }

    public function findDraft(Query $query): Query
    {
        return $query->where(['published' => 0]);
    }

    public function findTrend(Query $query): Query
    {
        return $query->find('published')
            ->contain(['Users', 'Tags'])
            ->order(['favorite_count' => 'DESC'])
            ->limit(20);
    }

    public function findLatest(Query $query): Query
    {
        return $query->find('published')
            ->contain(['Users', 'Tags'])
            ->order(['Articles.created' => 'DESC'])
            ->limit(20);
    }

    public function findTimeLine(Query $query, array $options): Query
    {
        return $query->find('published')
            ->contain(['Users', 'Tags'])
            ->order(['Articles.created' => 'DESC'])
            ->matching('Users.Followers', fn($q) => $q->where(['Followers.user_id' => $options['user_id']]))
            ->limit(20);
    }

    public function findSearch(Query $query, array $options): Query
    {
        $params = $options['params'];
        $query->find('published')
            ->contain(['Users', 'Tags']);
        if (!empty($params['q'])) {
            $Articles_Tags_table = TableRegistry::getTableLocator()->get('ArticlesTags');
            $subquery = $Articles_Tags_table->find()
                ->contain(['Tags'])
                ->where(fn (QueryExpression $exp) => $exp->equalFields('ArticlesTags.article_id', 'Articles.id'))
                ->where(['Tags.title LIKE' => '%' . $params['q'] . '%']);

            $query
                ->where(['OR' => [
                    ['title LIKE' => '%' . $params['q'] . '%'],
                    ['Users.username LIKE' => '%' . $params['q'] . '%'],
                    ['Users.nickname LIKE' => '%' . $params['q'] . '%'],
                    fn (QueryExpression $exp) => $exp->exists($subquery)
                ]]);
        }

        if (!empty($params['start_date'])) {
            $query
                ->where(['Articles.created >=' => $params['start_date']]);
        }

        if (!empty($params['end_date'])) {
            $query
                ->where(['Articles.created <=' => $params['end_date']]);
        }

        return $query;
    }

    public function findByUserId(Query $query, array $options): Query
    {
        return $query->find('published')
            ->where(['user_id' => $options['user_id']])
            ->contain(['Users', 'Tags']);
    }

    public function findDraftByUserId(Query $query, array $options): Query
    {
        return $query->find('draft')
            ->where(['user_id' => $options['user_id']])
            ->contain(['Users', 'Tags']);
    }

    public function findUserFavorites(Query $query, array $options): Query
    {
        return $query->find('published')
            ->contain(['Users', 'Tags'])
            ->matching('Favorites', fn ($q) => $q->where(['Favorites.user_id' => $options['user_id']]));
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('title')
            ->maxLength('title', 255, 'タイトルは255文字までです。')
            ->allowEmptyString('title')
            ->notEmptyString('title', '記事を公開する場合にはタイトルは必須項目です。', fn($context) => 
                !empty($context['data']['published'])
            );

        $validator
            ->scalar('body')
            ->allowEmptyString('body')
            ->notEmptyString('body', '記事を公開する場合には本文は必須項目です。', fn($context) => 
                !empty($context['data']['published'])
        );


        $validator
            ->boolean('published')
            ->notEmptyString('published', '下書きか公開かは必ず指定する必要があります。');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'Users', '存在しないユーザーIDです。'));

        return $rules;
    }
}
