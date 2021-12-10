<?php

namespace App\Http\Controllers\Comment;

use App\Models\Trip;
use App\Models\Comment;
use App\Traits\Attachments;
use App\Traits\ApiResponser;
use App\Traits\JSONResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CommentResource;
use App\Http\Requests\StoreCommentRequest;

class CommentController extends Controller
{
    use ApiResponser, JSONResponse, Attachments;
    
    public function __construct(Request $request) {

        $this->setResource(CommentResource::class);
    }

    public function store(StoreCommentRequest $request, $id) {

        $trip = Trip::find($id);

        if(!$trip)
            return $this->error(404, 'Not Found !');

        $comment = Comment::create([
            'trip_id' => $trip->id,
            'user_id' => Auth::id(),
            'content' => $request->content
        ]);

        if($request->hasFile('attachment')) {
            $is_stored = $this->storeAttachment($request->attachment, $comment->id, Comment::class, Auth::id());
            if(!$is_stored)
                return $this->error(300, "something wen't wrong !");
        }
        
        return $this->resource($comment);
    }

    public function destroy($id) {

        if(!$this->checkIfAuthorized($id))
            return $this->error(401, 'Unauthorized !');

        $comment = Comment::find($id);

        $comment->delete();

        return $this->success([], 'Deleted Successfully');
    }

    public function update(Request $request, $id) {

        if(!$this->checkIfAuthorized($id))
            return $this->error(401, 'Unauthorized !');

        $comment = Comment::find($id);

        $comment->update($request->all());

        return $this->resource($comment);
    } 

    public function checkIfAuthorized($id) {

        return Comment::find($id)->user_id === Auth::id();
    }
}
