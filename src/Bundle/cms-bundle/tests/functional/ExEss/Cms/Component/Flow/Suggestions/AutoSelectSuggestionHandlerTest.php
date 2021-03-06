<?php declare(strict_types=1);

namespace Test\CmsBundle\Functional\ExEss\Cms\Component\Flow\Suggestions;

use ExEss\Bundle\CmsBundle\Doctrine\Type\GeneratedFieldType;
use ExEss\Bundle\CmsBundle\Entity\Flow;
use ExEss\Bundle\CmsBundle\Component\Flow\Request\FlowAction;
use ExEss\Bundle\CmsBundle\Component\Flow\Response;
use ExEss\Bundle\CmsBundle\Component\Flow\Suggestions\AutoSelectSuggestionHandler;
use Test\CmsBundle\Helper\Testcase\FunctionalTestCase;

class AutoSelectSuggestionHandlerTest extends FunctionalTestCase
{
    private AutoSelectSuggestionHandler $handler;

    public function _before(): void
    {
        $this->handler = $this->tester->grabService(AutoSelectSuggestionHandler::class);
    }

    /**
     * @dataProvider suggestionDataProvider
     */
    public function testSelectSuggestion(
        array $fieldData,
        array $suggestions,
        array $expectedModel,
        bool $expectedReload = false
    ): void {
        // Given
        $flowId = $this->tester->generateFlow([
            'key_c' => 'some thing',
        ]);
        $fieldId = $this->tester->generateGuidanceField([
            "field_id" => 'my_field',
        ] + $fieldData);
        $flowStepId = $this->tester->generateFlowSteps($flowId);
        $this->tester->linkGuidanceFieldToFlowStep($fieldId, $flowStepId);

        $response = new Response();
        $response->setSuggestions(new Response\Suggestions());
        foreach ($suggestions as $field => $value) {
            $response->getSuggestions()->addFor($field, new Response\Suggestion\ValueSuggestion($value, $value));
        }

        // When
        $this->handler->handleModel(
            $response,
            new FlowAction(['event' => FlowAction::EVENT_CHANGED]),
            $this->tester->grabEntityFromRepository(Flow::class, ['id' => $flowId])
        );

        // Then
        $this->tester->assertEquals($expectedModel, $response->getModel()->toArray());
        $this->tester->assertEquals($expectedReload, $response->isForceReload());
    }

    public function suggestionDataProvider(): array
    {
        return [
            [
                [
                    "field_auto_select_suggestions" => true,
                ],
                [],
                [],
            ],[
                [
                    "field_auto_select_suggestions" => true,
                ],
                ['my_field' => 'foo'],
                ['my_field' => 'foo'],
            ],[
                [
                    "field_auto_select_suggestions" => true,
                ],
                ['not_my_field' => 'foo'],
                [],
            ],[
                [
                    "field_auto_select_suggestions" => false,
                ],
                ['my_field' => 'foo'],
                [],
            ],[
                [
                    "field_auto_select_suggestions" => true,
                    "field_multiple" => true,
                ],
                ['my_field' => 'foo'],
                ['my_field' => ['foo']],
            ],[
                [
                    "field_auto_select_suggestions" => true,
                    "field_generatetype" => GeneratedFieldType::REPEAT_TRIGGER,
                ],
                ['my_field' => 'foo'],
                ['my_field' => 'foo'],
                true
            ],
        ];
    }
}
