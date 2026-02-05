<?php

namespace Tests\Unit;

use App\Http\Requests\StoreTaskRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class StoreTaskRequestTest extends TestCase
{
    public function test_store_task_validation_rules()
    {
        $request = new StoreTaskRequest();
        $rules = $request->rules();

        $data = [
            'title' => '', // Empty title should fail
            'status' => 'invalid_status', // Invalid status should fail
        ];

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('title', $validator->errors()->messages());
        $this->assertArrayHasKey('status', $validator->errors()->messages());
    }

    public function test_store_task_validation_success()
    {
        $request = new StoreTaskRequest();
        $rules = $request->rules();

        $data = [
            'title' => 'Valid Title',
            'status' => 'pending',
            'due_date' => date('Y-m-d', strtotime('+1 day')),
        ];

        $validator = Validator::make($data, $rules);

        $this->assertFalse($validator->fails());
    }
}
