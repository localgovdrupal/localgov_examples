<?php

namespace Drupal\Tests\localgov_examples_base\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\user\UserInterface;

/**
 * Tests example content type.
 *
 * @group localgov_example
 */
class ContentTypeTest extends BrowserTestBase {

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
  protected UserInterface $adminUser;

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = [
    'localgov_examples_base',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->adminUser = $this->drupalCreateUser([
      'bypass node access',
      'administer nodes',
    ]);
  }

  /**
   * Test posting example content.
   */
  public function testPostLink() {
    $this->drupalLogin($this->adminUser);
    $this->drupalGet('node/add/localgov_example');
    $form = $this->getSession()->getPage();
    $form->fillField('edit-title-0-value', 'Example 1');
    $form->checkField('edit-status-value');
    $form->pressButton('edit-submit');
  }


}
