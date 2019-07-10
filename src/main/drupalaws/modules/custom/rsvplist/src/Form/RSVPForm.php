<?php
//File has actual form and inserts value in DB under submit handler
/**
 * @file
 * Contains \Drupal\rsvplist\Form\RSVPForm
 */
namespace Drupal\rsvplist\Form; //core\lib\Drupal\rsvplist\Form

use Drupal\Core\Database\Database; //sending info back to db
use Drupal\Core\Form\FormBase; //referring back to core,drupal8\core\lib\Drupal\Core\Form\FormBase.php
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a RSVP Email form.
 */
class RSVPForm extends FormBase {  //our class RSVPForm is adding things to a class FormBase of core

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'rsvplist_email_form'; //its id of our form
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) { //buildForm has 3 parameters
	$form['first_name'] = array( //building fields
      '#title' => t('First Name'), //for multilingual
      '#type' => 'textfield',
	  '#required' => TRUE,
    );
	
	$form['last_name'] = array( //building fields
      '#title' => t('Last Name'), //for multilingual
      '#type' => 'textfield',
    );
    
	$form['email'] = array( //building fields
      '#title' => t('Email address'), //for multilingual
      '#type' => 'email',
      '#size' => 25,
      '#description' => t("Email address of user."),
      '#required' => TRUE,
    );
	
	$form['submit'] = array( //submit button
      '#type' => 'submit',
      '#value' => t('RSVP'),
    );
    
    return $form;
  }

  /**
   * {@inheritdoc}
   */
   public function validateForm(array &$form, FormStateInterface $form_state) {
	$input =  $form_state->getValue('first_name');
	  //dsm($form);
	  //print_r($input);die();
	// echo"<pre>"; print_r($form);die();
	//if(strlen($form_state->getValue('first_name')) < 2 ) {
	if(strlen($input) < 2 ) {
	$form_state->setErrorByName('first_name', $this->t('First name can not be less than 2 digits.'));
    }
  } 

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
     $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());//grabs current user id
    db_insert('rsvplist')//inserts input in DB
      ->fields(array(
        'mail' => $form_state->getValue('email'),//get input mail
        'fname' => $form_state->getValue('first_name'),//get first name
        'lname' => $form_state->getValue('last_name'),//get last mail
        'uid' => $user->id(),//get user id value
        'created' => time(),//get time when created
       ))
      ->execute(); 
     drupal_set_message(t('Thank you for your RSVP, you are on the list for the event.')); 
  }
}
