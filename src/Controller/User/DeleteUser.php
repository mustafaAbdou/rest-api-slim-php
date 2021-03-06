<?php declare(strict_types=1);

namespace App\Controller\User;

use Slim\Http\Request;
use Slim\Http\Response;

class DeleteUser extends BaseUser
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $input = $request->getParsedBody();
        $userIdLogged = $input['decoded']->sub;
        $this->checkUserPermissions($args['id'], $userIdLogged);
        $user = $this->getUserService()->deleteUser((int) $args['id']);

        return $this->jsonResponse($response, 'success', $user, 204);
    }
}
