<?php

namespace Tests\Feature\ReportTemplates;

use App\Models\ReportTemplate;
use App\Models\User;
use Tests\Support\InteractsWithSettings;
use Tests\TestCase;

class DeleteReportTemplateTest extends TestCase
{
    use InteractsWithSettings;

    public function testDeletingReportTemplateRequiresCorrectPermission()
    {
        $this->actingAs(User::factory()->create())
            ->post(route('report-templates.destroy', 1))
            ->assertForbidden();
    }

    public function testCanDeleteAReportTemplate()
    {
        $user = User::factory()->canViewReports()->create();
        $reportTemplate = ReportTemplate::factory()->for($user)->create();

        $this->actingAs($user)
            ->delete(route('report-templates.destroy', $reportTemplate))
            ->assertRedirect(route('reports/custom'));

        $this->assertFalse($reportTemplate->exists());
    }
}
