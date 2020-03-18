<?php
namespace App\View\Cell;

use Cake\View\Cell;

/**
 * Ranking cell
 */
class RankingCell extends Cell
{
    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = [];

    /**
     * Initialization logic run at the end of object construction.
     *
     * @return void
     */
    public function initialize()
    {
        $this->loadModel('Users');
        $this->loadModel('Tags');
    }

    /**
     * Default display method.
     *
     * @return void
     */
    public function display()
    {
        $user_ranking = $this->Users->find()
            ->contain('Articles', fn($q) => $q->select([
                'user_id', 'favorite_count'
            ]));
        
        $user_ranking = $user_ranking->sortBy('total_favorite', SORT_DESC)->take(10);

        $tag_ranking = $this->Tags->find()
            ->order(['article_count' => 'DESC'])
            ->limit(10);

        $this->set(compact('user_ranking', 'tag_ranking'));
    }
}
