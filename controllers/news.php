<?php

use Application\Model\News;

$this->map('/news', function ($application) {
    $application->getView()->setScript('news/index');
    
    $newsModel = new News($application->getDb());
    
    return array(
        'news' => $newsModel->fetchAll()
    );
});

$this->map('/news/:id', function ($application) {
    $application->getView()->setScript('news/item');
    
    $newsModel = new News($application->getDb());
    
    $id = $application->getRequest()->getParam('id');
    
    return array(
        'item' => $newsModel->find($id),
        'comments' => $newsModel->fetchComments($id)
    );
});

$this->map('/news/:id/add-comment', function ($application) {
    $newsModel = new News($application->getDb());
    $request   = $application->getRequest();
    
    $id = (int)$request->getParam('id');
    $data = array(
        'username' => $request->getPost('username'),
        'text'     => $request->getPost('text'),
        'ip'       => $request->getClientIp(true)
    );
    
    $newsModel->addComment($id, $data);
    
    $application->getResponse()->setRedirect('/news/' . $id);
});