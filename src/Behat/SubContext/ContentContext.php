<?php
namespace Promet\Drupal\Behat\SubContext;

use Promet\Drupal\Behat\SubContext;

class ContentContext extends SubContext
{
    public $content;

    public static function getAlias() {
        return "DrupalContent";
    }

    /**
     * @Given /^(\d+) "([^"]*)" ([\w ]+) exist[s]?$/
     */
    public function createContent($amount, $bundle, $entityTypeLabel) {
        $entityTypeLabel = preg_replace("/s$/", "", $entityTypeLabel);
        $selectedEntityType = NULL;
        foreach (entity_get_info() as $entityType => $entityInfo) {
            if (strtolower($entityInfo['label']) == strtolower($entityTypeLabel)) {
                $selectedEntityType = $entityType;
                $selectedEntityInfo = $entityInfo;
                break;
            }
        }
        if (empty($selectedEntityType)) {
            throw new \Exception("Entity Type $entityTypeLabel doesn't exist.");
        }
        for ($i=0; $i<$amount; $i++) {
            $entity_object = entity_create(
                $selectedEntityType,
                array( $selectedEntityInfo['entity keys']['bundle'] => $bundle)
            );
            $wrapper = entity_metadata_wrapper($selectedEntityType, $entity_object);
            $wrapper->save();
            $this->content[$selectedEntityType][$bundle][$i] = $wrapper;
        }
    }
}
