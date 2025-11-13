<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        protected UserService $users
    ) {}


    /**
     * @OA\Get(
     *     path="/api/admin/users",
     *     summary="Felhasználók listázása",
     *     description="Admin oldalon az összes felhasználó lekérése.",
     *     tags={"Admin"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Felhasználók listája",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return response()->json($this->users->all());
    }


    /**
     * @OA\Post(
     *     path="/api/admin/users",
     *     summary="Új felhasználó létrehozása",
     *     tags={"Admin"},
     *     description="Admin létrehozhat új usert vagy admint is.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","username","password","role"},
     *             @OA\Property(property="name", type="string", example="Simon Béla"),
     *             @OA\Property(property="username", type="string", example="simonb"),
     *             @OA\Property(property="password", type="string", example="Password123!"),
     *             @OA\Property(property="password_confirmation", type="string", example="Password123!"),
     *             @OA\Property(property="role", type="string", enum={"admin","user"}, example="user")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Felhasználó sikeresen létrehozva",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validációs hiba",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'username' => 'required|unique:users,username',
            'password' => 'required|string|confirmed|min:8',
            'role'     => 'required|in:admin,user',
        ]);

        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $data['is_active'] = true;
        $data['failed_logins'] = 0;

        $user = $this->users->create($data);

        return response()->json($user, 201);
    }


    /**
     * @OA\Put(
     *     path="/api/admin/users/{id}",
     *     summary="Felhasználó módosítása",
     *     tags={"Admin"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Felhasználó azonosítója",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Módosított Név"),
     *             @OA\Property(property="password", type="string", example="NewSecret123!"),
     *             @OA\Property(property="password_confirmation", type="string", example="NewSecret123!"),
     *             @OA\Property(property="role", type="string", enum={"admin","user"}, example="admin")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Felhasználó frissítve",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $data = $request->except('password');

        if ($request->password) {
            $request->validate([
                'password' => 'confirmed|min:8',
            ]);
            $data['password'] = bcrypt($request->password);
        }

        $user = $this->users->update($id, $data);

        return response()->json($user);
    }


    /**
     * @OA\Delete(
     *     path="/api/admin/users/{id}",
     *     summary="Felhasználó törlése",
     *     description="Admin törölhet egy felhasználót.",
     *     tags={"Admin"},
     *
     *     @OA\Parameter(
     *         name="id", in="path", required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(response=204, description="Sikeres törlés")
     * )
     */
    public function destroy($id)
    {
        $this->users->delete($id);
        return response()->json([], 204);
    }


    /**
     * @OA\Post(
     *     path="/api/admin/users/{id}/lock",
     *     summary="Felhasználó zárolása",
     *     tags={"Admin"},
     *
     *     @OA\Parameter(
     *         name="id", in="path", required=true,
     *         description="User ID", @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Felhasználó zárolva",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     )
     * )
     */
    public function lock($id)
    {
        return response()->json($this->users->lock($id));
    }


    /**
     * @OA\Post(
     *     path="/api/admin/users/{id}/unlock",
     *     summary="Felhasználó feloldása",
     *     tags={"Admin"},
     *
     *     @OA\Parameter(
     *         name="id", in="path", required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Felhasználó feloldva",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     )
     * )
     */
    public function unlock($id)
    {
        return response()->json($this->users->unlock($id));
    }
}
