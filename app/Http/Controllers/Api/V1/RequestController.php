<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestStoreRequest;
use App\Http\Requests\RequestUpdateRequest;
use App\Http\Resources\RequestCollection;
use App\Http\Resources\RequestResource;
use App\Jobs\SendEmail;
use App\Models\RequestModel;
use App\QueryFilters\Comment;
use App\QueryFilters\CommentUserID;
use App\QueryFilters\CreatedAt;
use App\QueryFilters\CursorPaginateLoc;
use App\QueryFilters\Email;
use App\QueryFilters\ID;
use App\QueryFilters\Message;
use App\QueryFilters\Name;
use App\QueryFilters\Sort;
use App\QueryFilters\Status;
use Illuminate\Pipeline\Pipeline;
use OpenApi\Annotations as OA;

final class RequestController extends Controller
{

    /**
     * @OA\Get(
     *     summary="Get by filter request massage",
     *     path="/api/v1/requests",
     *     tags={"Request"},
     *     security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=false,
     *
     *      @OA\Schema(
     *          type="number"
     *      )
     *   ),
     *
     *   @OA\Parameter(
     *      name="id_from",
     *      in="query",
     *      required=false,
     *
     *      @OA\Schema(
     *          type="number"
     *      )
     *   ),
     *
     *   @OA\Parameter(
     *      name="id_to",
     *      in="query",
     *      required=false,
     *
     *      @OA\Schema(
     *          type="number"
     *      )
     *   ),
     *
     *   @OA\Parameter(
     *      name="name",
     *      in="query",
     *      required=false,
     *
     *      @OA\Schema(
     *          type="string",
     *      )
     *   ),
     *
     *   @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=false,
     *
     *      @OA\Schema(
     *          type="string",
     *      )
     *   ),
     *
     *   @OA\Parameter(
     *      name="message",
     *      in="query",
     *      required=false,
     *
     *      @OA\Schema(
     *          type="string",
     *      )
     *   ),
     *
     *   @OA\Parameter(
     *      name="comment",
     *      in="query",
     *      required=false,
     *
     *      @OA\Schema(
     *          type="string",
     *      )
     *   ),
     *
     *   @OA\Parameter(
     *      name="comment_user_id",
     *      in="query",
     *      required=false,
     *
     *      @OA\Schema(
     *          type="number"
     *      )
     *   ),
     *
     *   @OA\Parameter(
     *      name="status",
     *      in="query",
     *      required=false,
     *
     *      @OA\Schema(
     *          type="string",
     *          enum={"Active","Resolved"}
     *      )
     *   ),
     *
     *   @OA\Parameter(
     *      name="created_at",
     *      in="query",
     *      required=false,
     *
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *
     *   @OA\Parameter(
     *      name="created_at_from",
     *      in="query",
     *      required=false,
     *
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *
     *   @OA\Parameter(
     *      name="created_at_to",
     *      in="query",
     *      required=false,
     *
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *
     *   @OA\Parameter(
     *      name="sort",
     *      in="query",
     *      required=false,
     *
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *
     *   @OA\Parameter(
     *      name="limit",
     *      in="query",
     *      required=false,
     *
     *      @OA\Schema(
     *          type="number"
     *      )
     *   ),
     *
     *     @OA\Response(response="200", description="List."),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Not Found"),
     * )
     */
    public function index()
    {
        $request_model = app(Pipeline::class)
            ->send(
                RequestModel::query()->with(
                    [
                        'comment_user',
                    ]
                )
            )
            ->through(
                [
                    ID::class,
                    Name::class,
                    Email::class,
                    Message::class,
                    Comment::class,
                    CommentUserID::class,
                    Status::class,
                    CreatedAt::class,
                    Sort::class,
                    CursorPaginateLoc::class,
                ]
            )->thenReturn();
        return response()->success(
            new RequestCollection($request_model)
        );
    }


    /**
     * @OA\Post(
     *   path="/api/v1/requests",
     *   tags={"Request"},
     *   summary="store request",
     *
     *   @OA\Parameter(
     *      name="name",
     *      in="query",
     *      required=true,
     *
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *
     *   @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *
     *   @OA\Parameter(
     *      name="message",
     *      in="query",
     *      required=true,
     *
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   )
     * )
     **/
    public function store(RequestStoreRequest $request)
    {
        $data = $request->validated();
        $data['status'] = RequestModel::STATUS_ACTIVE;
        return response()->success(
            new RequestResource(RequestModel::query()->create($data))
        );
    }

    /**
     * @OA\Get(
     *   path="/api/v1/requests/{id}",
     *   tags={"Request"},
     *   summary="show request",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *
     *      @OA\Schema(
     *          type="number"
     *      )
     *   ),
     *
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   )
     * )
     **/
    public function show($id)
    {
        return response()->success(
            new RequestResource(RequestModel::query()->findOrFail($id))
        );
    }


    /**
     * @OA\Put(
     *   path="/api/v1/requests/{id}",
     *   tags={"Request"},
     *   summary="store request",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *
     *      @OA\Schema(
     *          type="number"
     *      )
     *   ),
     *
     *   @OA\Parameter(
     *      name="comment",
     *      in="query",
     *      required=true,
     *
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   )
     * )
     **/
    public function update(RequestUpdateRequest $request, $id)
    {
        $data = $request->validated();
        $data['status'] = RequestModel::STATUS_RESOLVED;
        $data['comment_user_id'] = auth()->id();
        $request_model = RequestModel::query()->findOrFail($id);
        $emailMessage['from'] = 'Div';
        $emailMessage['to'] = $request_model->email;
        $emailMessage['subject'] = 'Resolved';
        $emailMessage['body'] = $data['comment'];
        $request_model->update($data);
        dispatch(new SendEmail($emailMessage));

        return response()->success();
    }

    /**
     * @OA\Delete(
     *   path="/api/v1/requests/{id}",
     *   tags={"Request"},
     *   summary="delete request",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *
     *      @OA\Schema(
     *          type="number"
     *      )
     *   ),
     *
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   )
     * )
     **/
    public function destroy($id)
    {
        return response()->success(
            RequestModel::query()->findOrFail($id)
                ->delete()
        );
    }
}
