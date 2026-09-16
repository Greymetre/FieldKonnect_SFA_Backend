<?php

namespace Tests\Unit;

use App\Exports\LeadsTemplate;
use App\Imports\LeadsImport;
use PHPUnit\Framework\TestCase;

class LeadsImportTest extends TestCase
{
    public function test_only_firm_and_customer_names_are_required(): void
    {
        $rules = (new LeadsImport())->rules();

        $this->assertSame('required', $rules['firm_name']);
        $this->assertSame('required', $rules['customer_name']);

        unset($rules['firm_name'], $rules['customer_name']);

        foreach ($rules as $rule) {
            $this->assertStringContainsString('nullable', $rule);
            $this->assertStringNotContainsString('required', $rule);
        }
    }

    public function test_downloaded_template_matches_the_supported_import_format(): void
    {
        $this->assertSame([
            'Lead Generation Date',
            'Firm Name',
            'Customer Name',
            'Designation',
            'Customer Number',
            'Alternet Number',
            'Revenue (Rs Cr)',
            'Email',
            'Lead Source',
            'Pincode',
            'Place',
            'City',
            'District',
            'State',
            'Address',
            'Lead Type',
            'Assignee',
            'Note',
            'Website',
            'Others - 1',
            'Others - 2',
            'Others - 3',
            'Others - 4',
            'Others - 5',
        ], (new LeadsTemplate())->headings());
    }
}
