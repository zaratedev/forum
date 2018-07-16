<?php

namespace App\Http\Controllers;

use App\Inspections\Spam;
use App\Reply;
use App\Thread;
use Illuminate\Http\Request;

class RepliesController extends Controller
{

    /**
     * RepliesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    public function index($channelId, Thread $thread)
    {
      return $thread->replies()->paginate(20);
    }

    /**
     * @param $channelId
     * @param Thread $thread
     * @param Spam $spam
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($channelId, Thread $thread)
    {
        try {
            $this->validate(request(), ['body' => 'required|spamfree']);

            $reply = $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id()
            ]);
        }
        catch (\Exception $e) {
            return response('Sorry, your reply could not saved at this time.', 422);
        }

        return $reply->load('owner');
    }

    public function destroy(Reply $reply)
    {
      $this->authorize('update', $reply);

      $reply->delete();

      if (request()->expectsJson()) {
        return response(['status' => 'Reply deleted.']);
      }

      return back();
    }

    public function update(Reply $reply, Spam $spam)
    {
        try {
            $this->authorize('update', $reply);

            $spam->detect(request('body'));

            $reply->update(['body' => request('body')]);
        }
        catch (\Exception $e)
        {
            return response('Sorry, your reply could not saved at this time.', 422);
        }
    }
}
