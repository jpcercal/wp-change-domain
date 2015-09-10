<?php

namespace Cekurte\Wordpress\ChangeDomain\Controller;

use Cekurte\Wordpress\ChangeDomain\Builder\SqlBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class ApiController extends WebController
{
    public function indexAction(Request $request)
    {
        $errors = $this->getApp()['validator']->validateValue($request, $this->getValidatorConstraints());

        if (count($errors) > 0) {
            $data = [];

            foreach ($errors as $error) {
                $data[str_replace(['[', ']'], '', $error->getPropertyPath())] = $error->getMessage();
            }

            return new JsonResponse($data, 400);
        }

        $builder = new SqlBuilder($request);

        return new JsonResponse(['sql' => $builder->getSqlQueries()]);
    }

    private function getValidatorConstraints()
    {
        return [
            new Assert\Type(['type' => '\\Symfony\\Component\\HttpFoundation\\Request']),
            new Assert\Callback(function ($request, $context) {

                $validator = $context->getValidator();

                $data = json_decode($request->getContent(), true);

                $errors = $validator->validate($data, new Assert\Collection([
                    'fields' => [
                        'tablePrefix' => [
                            new Assert\NotBlank(),
                        ],
                        'numberOfBlogs' => [
                            new Assert\NotBlank(),
                        ],
                        'domainFrom' => [
                            new Assert\NotBlank(),
                            new Assert\Url(),
                        ],
                        'domainTo' => [
                            new Assert\NotBlank(),
                            new Assert\Url(),
                        ],
                    ],
                ]));

                foreach ($errors as $error) {
                    $context->buildViolation($error->getMessage())
                        ->atPath($error->getPropertyPath())
                        ->setCode($error->getCode())
                        ->setInvalidValue($error->getInvalidValue())
                        ->setParameters($error->getParameters())
                        ->setCause($error->getCause())
                        ->addViolation()
                    ;
                }
            }),
        ];
    }
}
