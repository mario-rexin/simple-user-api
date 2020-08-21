<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\UserRepository;

class UserController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/users", name="add_user", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $name = $data['name'];
        $email = $data['email'];

        if (empty($name)) {
            throw new NotFoundHttpException('expecting mandatory parameters');
        }

        $this->userRepository->saveUser($name, $email);

        return new JsonResponse(['status' => 'User created'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/users/{id}", name="get_one_user", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['id' => $id]);

        if (empty($user)) {
            return new JsonResponse(['status' => 'user not found'], Response::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail()
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/users", name="get_all_users", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $users = $this->userRepository->findAll();
        $data = [];

        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail()
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/users/{id}", name="update_user", methods={"PUT"})
     */
    public function update($id, Request $request): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['id' => $id]);

        if (empty($user)) {
            return new JsonResponse(['status' => 'user not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        empty($data['name']) ? true : $user->setName($data['name']);
        empty($data['email']) ? true : $user->setEmail($data['email']);

        $updatedUser = $this->userRepository->updateUser($user);

        return new JsonResponse($updatedUser->toArray(), Response::HTTP_OK);
    }

    /**
     * @Route("/users/{id}", name="delete_user", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['id' => $id]);

        if (empty($user)) {
            return new JsonResponse(['status' => 'user not found'], Response::HTTP_NOT_FOUND);
        }

        $this->userRepository->removeUser($user);

        return new JsonResponse(['status' => 'user deleted'], Response::HTTP_NO_CONTENT);
    }
}
