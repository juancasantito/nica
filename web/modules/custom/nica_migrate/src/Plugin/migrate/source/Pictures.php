<?php
/**
 * @file
 * Contains \Drupal\nica_migrate\Plugin\migrate\source\Pictures.
 */

namespace Drupal\nica_migrate\Plugin\migrate\source;

use Drupal\Core\Site\Settings;
use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;
use Drupal\migrate\MigrateException;
use Drupal\flysystem_dropbox\Flysystem\Dropbox;

/**
 *
 * @MigrateSource(
 *   id = "pictures"
 * )
 */
class Pictures extends SourcePluginBase {

  /**
   * {@inheritdoc}
   */
  public function initializeIterator() {
    $settings = Settings::get('flysystem', []);
    $configuration = [];
      foreach ($settings as $scheme) {
        if ($scheme['driver'] == 'dropbox') {
          $configuration = $scheme['config'];
        }
      }
    if (empty($configuration)) {
      throw new MigrateException('There is not configuration for dropbox stream wrapper');
    }
    $f = key($settings);
    $dropbox = new Dropbox($configuration);
    $client = $dropbox->getAdapter()->getClient();
    $directory = $client->getMetadataWithChildren($this->configuration['path']);
    if (isset($directory['contents'])) {
      foreach ($directory['contents'] as $key => $info) {
        $file_name = explode('/', $directory['contents'][$key]['path']);
        $file_name_original = $file_name[count($file_name) - 1];
        $file_name =  strtoupper($file_name_original);
        preg_match('/-(.*)(.JPG|.GIF|.PNG)/', $file_name, $name_tmp);
        isset($name_tmp[1]) ? $file_name = trim($name_tmp[1]) : $file_name;
        $directory['contents'][$key]['filename'] = $file_name;
        $directory['contents'][$key]['filepath'] = key($settings) . ':/' . $directory['contents'][$key]['path'];
        $directory['contents'][$key]['uri'] = 'public://perfil/pictures/' . $file_name_original;
      }
      return new \ArrayIterator($directory['contents']);
    }
    else {
      throw new MigrateException('There is not images in dropbox file.');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getIDs() {
    $ids = [];
    $ids['filename']['type'] = 'string';
    return $ids;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    // TODO: Implement fields() method.
    return array(
      'filename' => $this->t('File name'),
      'filepath' => $this->t('File path'),
    );
  }

  /**
   * Return a string representing the source query.
   *
   * @return string
   *   The file path.
   */
  public function __toString() {
    // TODO: Implement __toString() method.
    return $this->configuration['path'];
  }
}
