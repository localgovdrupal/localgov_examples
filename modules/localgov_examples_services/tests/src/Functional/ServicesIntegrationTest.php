<?php

namespace Drupal\Tests\localgov_examples_services\Functional;

use Drupal\node\NodeInterface;
use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\node\Traits\NodeCreationTrait;
use Drupal\Tests\system\Functional\Menu\AssertBreadcrumbTrait;

/**
 * Tests pages working together with pathauto, services.
 *
 * @group localgov_examples
 */
class ServicesIntegrationTest extends BrowserTestBase {

  use NodeCreationTrait;
  use AssertBreadcrumbTrait;

  /**
   * {@inheritdoc}
   */
  protected $profile = 'testing';

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * A user with permission to bypass content access checks.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * The node storage.
   *
   * @var \Drupal\node\NodeStorageInterface
   */
  protected $nodeStorage;

  /**
   * Module installer.
   *
   * @var \Drupal\Core\Extension\ModuleInstallerInterface
   */
  protected $moduleInstaller;

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = [
    'localgov_core',
    'localgov_examples_base',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->drupalPlaceBlock('system_breadcrumb_block');
    $this->adminUser = $this->drupalCreateUser([
      'bypass node access',
      'administer nodes',
    ]);
    $this->nodeStorage = $this->container->get('entity_type.manager')->getStorage('node');
    $this->moduleInstaller = $this->container->get('module_installer');
  }

  /**
   * Check services integration when services module enabled first.
   */
  public function testServicesIntegrationBefore() {
    $this->moduleInstaller->install([
      'localgov_services_landing',
      'localgov_services_sublanding',
      'localgov_services_navigation',
    ]);
    $this->moduleInstaller->install([
      'localgov_examples_services',
    ]);

    $this->checkServiceField();
  }

  /**
   * Check services integration when services module enabled after.
   */
  public function testServicesIntegrationAfter() {
    $this->moduleInstaller->install([
      'localgov_examples_services',
    ]);
    $this->moduleInstaller->install([
      'localgov_services_landing',
      'localgov_services_sublanding',
      'localgov_services_navigation',
    ]);

    $this->checkServiceField();
  }

  /**
   * Tests to confirm services field added to content type, and working.
   */
  private function checkServiceField() {
    $landing = $this->createNode([
      'title' => 'Landing Page 1',
      'type' => 'localgov_services_landing',
      'status' => NodeInterface::PUBLISHED,
    ]);
    $sublanding = $this->createNode([
      'title' => 'Sublanding 1',
      'type' => 'localgov_services_sublanding',
      'status' => NodeInterface::PUBLISHED,
      'localgov_services_parent' => ['target_id' => $landing->id()],
    ]);

    $this->drupalLogin($this->adminUser);
    $this->drupalGet('node/add/localgov_example');
    $form = $this->getSession()->getPage();
    $form->fillField('edit-title-0-value', 'Example page title');
    $form->fillField('edit-localgov-services-parent-0-target-id', "Sublanding 1 ({$sublanding->id()})");
    $form->checkField('edit-status-value');
    $form->pressButton('edit-submit');

    $this->assertSession()->pageTextContains('Example page title');
    $trail = ['' => 'Home'];
    $trail += ['landing-page-1' => 'Landing Page 1'];
    $trail += ['landing-page-1/sublanding-1' => 'Sublanding 1'];
    $this->assertBreadcrumb(NULL, $trail);
  }

}
