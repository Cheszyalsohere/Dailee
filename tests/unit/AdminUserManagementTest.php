<?php

namespace Tests\Unit;

use App\Controllers\Admin;
use CodeIgniter\Test\CIUnitTestCase;

class AdminUserManagementTest extends CIUnitTestCase
{
    public function testAdminControllerProvidesUserManagementMethods(): void
    {
        $reflection = new \ReflectionClass(Admin::class);

        $this->assertTrue($reflection->hasMethod('usersIndex'));
        $this->assertTrue($reflection->hasMethod('userCreate'));
        $this->assertTrue($reflection->hasMethod('userStore'));
        $this->assertTrue($reflection->hasMethod('userEdit'));
        $this->assertTrue($reflection->hasMethod('userUpdate'));
        $this->assertTrue($reflection->hasMethod('toggleUserStatus'));
    }

    public function testUserModelSupportsActiveStatusField(): void
    {
        $source = file_get_contents(ROOTPATH . 'app/Models/UserModel.php');

        $this->assertStringContainsString("'is_active'", $source);
    }
}
