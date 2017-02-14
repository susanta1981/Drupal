<?php
/*
 * @file document_download.resource.inc
 * 
 * Callback function for listing common documents
 * 
 */
function document_download_list_old($data) {
  if (empty($data['project_id']) || empty($data['unit_id'])) return array();
  $view = views_get_view('common_document_service');
  $view->set_display('default');
  $view->set_arguments(array($data['project_id']));
  $filter = $view->display_handler->get_option('filters');
  $filter['field_customer_unit_target_id_1']['value']['value'] = $data['unit_id'];
  $view->display_handler->override_option('filters', $filter);
  $view->execute();
  $result = $view->result;
  $output = array();
  if (!empty($result)) {
    foreach ($result as $document) {
      $file = $document->field_field_upload_document[0]['raw'];

      $file['title'] = $document->node_title;
      $file['path'] = $document->field_field_upload_document[0]['rendered']['#markup'];

      $output[] = $file;
    }
  }
  else {
    $output = "No documents found";
  }
  return $output;
}

function document_download_list($data) {
  if (empty($data['project_id'])) return array();
  $view = views_get_view('common_document_service');
  $view->set_display('default');
  $view->set_arguments(array($data['project_id']));
  $view->execute();
  $result = $view->result;
  $output = array();
  if (!empty($result)) {
    foreach ($result as $document) {
      $file = $document->field_field_upload_document[0]['raw'];

      $file['title'] = $document->node_title;
      $file['path'] = $document->field_field_upload_document[0]['rendered']['#markup'];

      $output[] = $file;
    }
  }
  else {
    $output = "No documents found";
  }
  return $output;
}
