<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class UserComponent extends Component
{
    public function countFavorite(array $articles)
    {
        return array_reduce($articles, function($total, $current) {
			return $total += $current->favorite_count;
		});
    }

    public function isFollowed(int $follow_user_id, ?int $user_id)
    {
        $follows_table = TableRegistry::getTableLocator()->get('Follows');
        return (bool)$follows_table->find()
            ->where(['follow_user_id' => $follow_user_id, 'user_id' => $user_id])
            ->first();
    }
}