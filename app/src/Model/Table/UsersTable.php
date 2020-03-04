<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

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
            ->maxLength('username', 32)
            ->requirePresence('username', 'create')
            ->notEmptyString('username');

        $validator
            ->scalar('password')
            ->lengthBetween('password', [6, 20], 'パスワードは6文字以上20文字以下にする必要があります。')
            ->requirePresence('password', 'create')
            ->notEmptyString('password')
            ->add('password', 'alphaNumeric', [
                'rule' => fn($data) => (bool)preg_match('/^[a-zA-Z0-9]+$/', $data),
                'message' => 'パスワードには半角英数字のみ使用できます。'
            ])
            ->add('password', 'requreAlphaNumeric',[
                'rule' => fn($data) => (bool)preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]/i', $data),
                'message' => 'パスワードは英文字、数字それぞれ1文字以上含める必要があります。'
            ]);

        $validator
            ->scalar('nickname')
            ->maxLength('nickname', 32)
            ->allowEmptyString('nickname');

        $validator
            ->email('email')
            ->maxLength('email', 255)
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
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
