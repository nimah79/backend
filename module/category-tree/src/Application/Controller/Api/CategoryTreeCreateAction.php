<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Application\Controller\Api;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\CategoryTree\Application\Form\CategoryTreeCreateForm;
use Ergonode\CategoryTree\Application\Model\CategoryTreeCreateFormModel;
use Ergonode\CategoryTree\Domain\Command\CreateTreeCommand;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\CategoryTree\Domain\Repository\TreeRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/trees", methods={"POST"})
 */
class CategoryTreeCreateAction
{
    /**
     * @var TreeRepositoryInterface
     */
    private $treeRepository;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @param TreeRepositoryInterface $treeRepository
     * @param MessageBusInterface     $messageBus
     * @param FormFactoryInterface    $formFactory
     */
    public function __construct(
        TreeRepositoryInterface $treeRepository,
        MessageBusInterface $messageBus,
        FormFactoryInterface $formFactory
    ) {
        $this->treeRepository = $treeRepository;
        $this->messageBus = $messageBus;
        $this->formFactory = $formFactory;
    }

    /**
     * @IsGranted("CATEGORY_TREE_CREATE")
     *
     * @SWG\Tag(name="Tree")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Category tree body",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/tree_req")
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Create category tree",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/error_response")
     * )
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     *
     * @todo Validation required
     */
    public function __invoke(Request $request): Response
    {
        $model = new CategoryTreeCreateFormModel();
        $form = $this->formFactory->create(CategoryTreeCreateForm::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $tree = $this->treeRepository->exists(CategoryTreeId::fromKey($data->code));

            if (!$tree) {
                $command = new CreateTreeCommand($data->code, new TranslatableString($data->name));
                $this->messageBus->dispatch($command);

                return new CreatedResponse($command->getId());
            }

            throw new BadRequestHttpException('Tree already exists');
        }

        throw new FormValidationHttpException($form);
    }
}
