<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Story;

use Validator;

class CommentController extends Controller
{
    public function store(Request $request, $type, $id)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required',
            'comment_type' => 'required|numeric',
            'image_path' => 'required_if:comment_type,2',
        ]);

        if ($validator->fails()) {
            return response()
                ->json(['statuscode' => false, 'errors' => $validator->errors()->all()]);
        }
        try {
            $commentType = $request->input('comment_type');
            if ($commentType == 2) {
                $commentText = $request->input('comment') . '<br />' . '<img src="' . $request->input('image_path') . '" alt="comment" />';
            } else if ($commentType == 1) {
                $commentText = $request->input('comment');
            } else {
                $commentText = $request->input('comment');
            }

            $type = ucfirst($type); // Convert to uppercase

            $modelName = "\App\Models\\$type";

            $modelInstance = $modelName::find($id);
            $comment = $modelInstance->comment($commentText, $commentType);

            if ($comment) {
                return response()->json(['message' => 'Comment Created Successfully', 'comment' => $comment]);
            } else {
                abort(400);
            }
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }
    //
}
