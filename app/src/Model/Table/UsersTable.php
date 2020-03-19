<?php
namespace App\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Mailer\MailerAwareTrait;

/**
 * Users Model
 *
 * @property \App\Model\Table\ArticlesTable&\Cake\ORM\Association\HasMany $Articles
 * @property \App\Model\Table\CommentsTable&\Cake\ORM\Association\HasMany $Comments
 * @property \App\Model\Table\FavoritesTable&\Cake\ORM\Association\HasMany $Favorites
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
{
    use MailerAwareTrait;
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Articles', [
            'foreignKey' => 'user_id',
            'dependent' => true,
            'cascadeCallbacks' => true
        ]);
        $this->hasMany('Comments', [
            'foreignKey' => 'user_id',
            'dependent' => true
        ]);
        $this->hasMany('Favorites', [
            'foreignKey' => 'user_id',
            'dependent' => true
        ]);

        $this->hasMany('Follows', [
            'className' => 'Follows',
            'foreignKey' => 'user_id',
            'dependent' => true,
        ]);

        $this->hasMany('Followers', [
            'className' => 'Follows',
            'foreignKey' => 'follow_user_id',
            'dependent' => true,
        ]);

        $this->belongsToMany('Tags', [
            'dependent' => true,
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'tag_id',
            'joinTable' => 'users_tags',
        ]);
    }

    public function findSearch(Query $query, array $options): Query
    {
        $params = $options['params'];
        $query->find('userRanking')
            ->contain('Articles', fn($q) => $q->select(['user_id', 'favorite_count']));
        if (empty($params['q'])) return $query;

        return $query
            ->where(['OR' => [
                ['Users.username LIKE' => '%' . $params['q'] . '%'],
                ['Users.nickname LIKE' => '%' . $params['q'] . '%'],
            ]]);
    }

    public function findUserRanking(Query $query): Query
    {
        return $query->select(['total_favorite' => $query->func()->sum('Articles.favorite_count')])
            ->leftJoinWith('Articles')
            ->group(['Users.id'])
            ->enableAutoFields(true);
    }

    public function findByFavorite(Query $query, array $options): Query
    {
        return $query->contain('Articles', fn($q) => $q->select(['user_id', 'favorite_count']))
            ->matching('Favorites', fn($q) => $q->where(['Favorites.article_id' => $options['article_id']]));
    }

    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        // パスワード変更が変更されたときに通知メールを送る
        if (!$entity->isNew() && $entity->isDirty('password')) {
            $this->getMailer('User')->send('changePassword', [$entity]);
        }
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
            ->scalar('username')
            ->maxLength('username', 32, 'ユーザー名は32文字までです。')
            ->requirePresence('username', 'create', 'ユーザー名が入力されていません。')
            ->notEmptyString('username', 'ユーザー名が入力されていません。')
            ->regex('username', '/[a-z0-9_-]/', 'ユーザー名は英数字-_のみ使用できます。');

        $validator
            ->scalar('password')
            ->lengthBetween('password', [6, 20], 'パスワードは6文字以上20文字以下にする必要があります。')
            ->requirePresence('password', 'create', 'パスワードが入力されていません。')
            ->notEmptyString('password', 'パスワードが入力されていません。')
            ->add('password', 'alphaNumeric', [
                'rule' => fn($data) => (bool)preg_match('/^[a-zA-Z0-9]+$/', $data),
                'message' => 'パスワードには半角英数字のみ使用できます。'
            ])
            ->regex('password', '/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]/i', 'パスワードは英文字、数字それぞれ1文字以上含める必要があります。');

        $validator
            ->scalar('nickname')
            ->maxLength('nickname', 32, '名前は32文字までです。')
            ->allowEmptyString('nickname');

        $validator
            ->email('email', false,  'メールの形式が正しくありません。')
            ->maxLength('email', 255, 'メールアドレスは255文字までです。')
            ->requirePresence('email', 'create', 'メールアドレスが入力されていません。')
            ->notEmptyString('email', 'メールアドレスが入力されていません。');

        $validator
            ->scalar('description')
            ->maxLength('description', 255, '自己紹介は255文字までです。')
            ->allowEmptyString('description');

        $validator
            ->scalar('link')
            ->urlWithProtocol('link', 'リンクはURLの形式である必要があります。')
            ->maxLength('link', 255, 'リンクは255文字までです。')
            ->allowEmptyString('link');

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
        $rules->add($rules->isUnique(['username'], 'このユーザー名はすでに使われています。'));
        $rules->add($rules->isUnique(['email'], 'このメールアドレスはすでに使われています。'));

        return $rules;
    }
}
