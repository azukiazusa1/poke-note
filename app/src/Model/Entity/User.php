<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Collection\Collection;
use Cake\ORM\TableRegistry;
use Token\Model\Entity\TokenTrait;

/**
 * User Entity
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string|null $nickname
 * @property string $email
 * @property string $desctiption
 * @property string $link
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Article[] $articles
 * @property \App\Model\Entity\Comment[] $comments
 * @property \App\Model\Entity\Favorite[] $favorites
 */
class User extends Entity
{
    use TokenTrait;
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'username' => true,
        'password' => true,
        'nickname' => true,
        'email' => true,
        'image' => true,
        'description' => true,
        'link' => true,
        'created' => true,
        'modified' => true,
        'article_count' => true,
        'follow_count' => true,
        'follower_count' => true,
        'articles' => true,
        'comments' => true,
        'favorites' => true,
        'tags' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password',
        'email'
    ];

    protected $_virtual = ['total_favorite'];

    protected function _setPassword($value) 
    {
        if (strlen($value)) {
            $hasher = new DefaultPasswordHasher();
            return $hasher->hash($value);
        }
    }

    /**
     * このユーザーが引数のユーザーをフォローしているか
     *
     * @param integer $user_id
     * @return boolean
     */
    public function isFollowed(?int $user_id): bool
    {
        if (!isset($user_id)) return false;
        $follows_table = TableRegistry::getTableLocator()->get('Follows');
        return (bool)$follows_table->find()
            ->where(['follow_user_id' => $this->id, 'user_id' => $user_id])
            ->first();
    }

    /**
     *  使用したことがあるタグか
     *
     * @param integer $tag_id
     * @return boolean
     */
    public function isUsedTag(int $tag_id): bool
    {
        if (!$this->articles) return false;
        $articles = new Collection($this->articles);
        return $articles->some(function ($article) use ($tag_id) {
            if (!$article->tags) return false;
            $tags = new Collection($article->tags);
            return $tags->some(fn($tag) => $tag->id === $tag_id);
        });
    }

    /**
     * フォローしているタグ化
     *
     * @param integer $tag_id
     * @return boolean
     */
    public function isFollowedTag(int $tag_id): bool
    {
        $users_tags = TableRegistry::getTableLocator()->get('UsersTags');
        return (bool)$users_tags->find()
            ->where(['usre_id' => $this->id, 'tag_id' => $tag_id])
            ->first();
    }

    protected function _getTotalFavorite(): int
    {
        if (!$this->articles) return 0;
        return array_reduce($this->articles, fn($total, $current) => $total += $current->favorite_count);
    }
}
