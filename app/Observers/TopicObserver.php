<?php

namespace App\Observers;

use App\Handlers\SlugTranslateHandler;
use App\Models\Topic;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function creating(Topic $topic)
    {
        //
    }

    public function updating(Topic $topic)
    {
        //
    }

    public function saving(Topic $topic)
    {
        $topic->body    = clean($topic->body, 'user_topic_body');

        $topic->excerpt = make_excerpt($topic->body);

        // 如果 slug 为空， 则调用翻译器 对 title 进行翻译
        if ( ! $topic->slug ) {
            $topic->slug = app(SlugTranslateHandler::class)->translate($topic->title);
        }
    }
}