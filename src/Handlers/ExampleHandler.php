<?php

namespace App\Handlers;

use App\Utils\Logger;
use App\Services\DbService;
use App\Services\EmployeeService;
use App\Services\SenseiService;
use App\Services\AsyncAssignTaskService;
use App\Utils\ResultBuilder;

class ExampleHandler
{
    private $leadId;
    private $pipelineId;
    private $employeeName;
    private $taskPriority;
    private $taskStatus;


    public function __construct(array $leadData)
    {
        $this->leadId = $leadData['lead_id'] ?? null;
        $this->pipelineId = $leadData['pipeline_id'] ?? null;
        $this->employeeName = $leadData['pricing_name'] ?? null;
        $this->taskPriority = $leadData['priority'] ?? null;
        $this->taskStatus = $leadData['task_status'] ?? null;
    }

    public function exmaple()
    {
        // Сценарий
        return ResultBuilder::success("Успех");
    }


}