<?php

namespace Drupal\draco_analytics\Form;


use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class DracoMetaConfig extends ConfigFormBase {
    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'draco_analytics_metadata';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() {
        return [
            'draco_analytics.metadata',
        ];
    }
    

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $config = $this->config('draco_analytics.metadata');

        $form['article_author'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Article Author'),
            '#default_value' => $config->get('article_author'),
        );
		$form['article_publish_date'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Article Publich date'),
            '#default_value' => $config->get('article_publish_date'),
        );
		$form['attribution'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Attribution'),
            '#default_value' => $config->get('attribution'),
        );
		



        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $values = $form_state->getValues();
        $this->config('draco_analytics.metadata')
            ->set('article_author', $values['article_author'])
            ->set('article_publish_date', $values['article_publish_date'])
            ->set('attribution', $values['attribution'])
            ->save();

    }
}

