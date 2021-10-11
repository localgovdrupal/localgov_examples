<?php

namespace Drupal\Tests\localgov_examples_base\Functional;

use Drupal\Core\Url;
use Drupal\FunctionalTests\Update\UpdatePathTestBase;

/**
 * Test LocalGov example updates.
 */
class ExamplesUpdateTest extends UpdatePathTestBase {

  /**
   * {@inheritdoc}
   */
  public function setDatabaseDumpFiles() {
    // Database dump files are created from the
    // php ./core/scripts/db-tools.php dump-database-d8-mysql
    // command.
    $this->databaseDumpFiles = [
      __DIR__ . '/../../fixtures/localgov_examples.pre-8000.php.gz',
    ];
  }

  /**
   * Tests LocalGov Base View mode update.
   */
  public function testUpdate() {

    // Test Drupal update to latest version.
    $this->runUpdates();
    $this->rebuildContainer();

    // A GUI check.
    $this->drupalLogin($this->rootUser);
    $this->drupalGet(Url::fromRoute('entity.entity_view_mode.edit_form', ['entity_view_mode' => 'node.localgov_example']));
    $this->assertSession()->fieldValueEquals('edit-label', 'Example view mode');

    // A code check.
    $view_mode = $this->container->get('config.factory')->get('core.entity_view_mode.node.localgov_example');
    $this->assertEquals('Example view mode', $view_mode->get('label'));
    $this->assertNotEmpty($view_mode->get('uuid'));
  }

}
