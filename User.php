<?php

class User {

  private $lastName;
  private $firstName;
  private $userName;
  private $password;
  private $secretQuestion;
  private $answer;

  public function setLastName($lastName) {
    if (strlen($lastName) < 2 || strlen($lastName) > 10) {
      throw new Exception("Nom non valide");
    }

    $this->lastName = $lastName;
  }

  public function setFirstName($firstName) {
    if (strlen($firstName) < 2 || strlen($firstName) > 10) {
      throw new Exception("Prénom non valide");
    }

    $this->firstName = $firstName;
  }

  public function setUserName($userName) {
    if (strlen($userName) < 2 || strlen($userName) > 10) {
      throw new Exception("Username non valide");
    }

    $this->userName = $userName;
  }

  public function setPassword($password) {
    if (strlen($password) < 3 || strlen($password) > 10 ) {
      throw new Exception("Mot de passe non valide");
    }

    $this->password = password_hash($password, PASSWORD_DEFAULT);
  }

  public function setSecretQuestion($secretQuestion, $answer) {
    switch($secretQuestion) {
      case 1:
        $secretQuestion = "Quelle est votre couleur préférée?";
        if (!isset($answer) || strlen($answer) < 3) {
          throw new Exception("Réponse non valide");
        } 
        $this->secretQuestion = $secretQuestion;
        $this->answer = $answer;
      break;
      case 2:
        $secretQuestion = "Quel est le nom de votre mère?";
        if (!isset($answer) || strlen($answer) < 3) {
          throw new Exception("Réponse non valide");
        } 
        $this->secretQuestion = $secretQuestion;
        $this->answer = $answer;
      break;
      case 3:
        $secretQuestion = "Où se trouve votre ville natale?";
        if (!isset($answer) || strlen($answer) < 3) {
          throw new Exception("Réponse non valide");
        } 
        $this->secretQuestion = $secretQuestion;
        $this->answer = $answer;
      break;
    }
  }

  public function saveToDatabase() {
    if (!isset($this->lastName) || !isset($this->firstName) || !isset($this->userName) || !isset($this->password) || !isset($this->secretQuestion) || !isset($this->answer)) {
      throw new Exception("Valeurs non valides");
    }
    
   require_once('connect.php');

    $sql = 'INSERT INTO accounts (last_name, first_name, username, password, secret_question, answer) VALUES (?, ?, ?, ?, ?, ?)';
    $statement = $connection->prepare($sql);
    $statement->execute([$this->lastName, $this->firstName, $this->userName, $this->password, $this->secretQuestion, $this->answer]);
  }
}
?>