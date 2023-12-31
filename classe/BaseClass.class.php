<?php
class BaseClass{
    protected $_currentX;
    protected $_currentY;
    protected $_currentAngle;
    protected $_dbh;
    protected $_actionStatus;

    // Appel de la class DataBase et assignation des variables de bases
    public function __construct() {
        $this->_dbh = new Database();
        $this->_currentX = 0;
        $this->_currentY = 1;
        $this->_currentAngle = 0;
        $this->_actionStatus = 0;

    }

    // === currentX ===
    public function setCurrentX($_currentX){
        $this->_currentX = $_currentX;
    }
    public function getCurrentX(){
        return $this->_currentX;
    }

    // === currentY ===
    public function setCurrentY($_currentY){
        $this->_currentY = $_currentY;
    }
    public function getCurrentY(){
        return $this->_currentY;
    }

    // === currentAngle ===
    public function setCurrentAngle($_currentAngle){
        $this->_currentAngle = $_currentAngle;
    }
    public function getCurrentAngle(){
        return $this->_currentAngle;
    }
    // === actionStatus ===
    public function setActionStatus($actionStatus){
            $this->_actionStatus = $actionStatus;
    }

    // Fonction de vérification du movement si il est possible ou non
    //@param newX newY newAngle sont les futurs mouvements du personnage 
    public function _checkMove(int $newX, int $newY, int $newAngle) {
        $sql = "SELECT * FROM map WHERE coordx=:currentX AND coordy=:currentY AND direction=:currentAngle";
        $query = $this->_dbh->prepare($sql);
        $query->bindParam(':currentX', $newX, PDO::PARAM_INT);
        $query->bindParam(':currentY', $newY, PDO::PARAM_INT);
        $query->bindParam(':currentAngle', $newAngle, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        if (!empty($result)){
            return true;
        }else{
            return false;
        }
    }
    // Assignation des propriétés newX et newY et incrémentation de ces dernières
    public function checkForward(){
        $newX = $this->_currentX;
        $newY = $this->_currentY;
            switch ($this->_currentAngle){
                case 0:
                    $newX++;
                    break;
                case 90:
                    $newY++;
                    break;
                case 180:
                    $newX--;
                    break;
                case 270:
                    $newY--;
                    break;
            }
            return $this->_checkMove($newX, $newY, $this->_currentAngle);
        }
    // Execution du mouvement
    public function goForward(){
        if ($this->checkForward() == true){
            switch ($this->_currentAngle){
                case 0:
                    $this->_currentX++;
                    break;
                case 90:
                    $this->_currentY++;
                    break;
                case 180:
                    $this->_currentX--;
                    break;
                case 270:
                    $this->_currentY--;
                    break;
            }
        }
    }

    public function checkBack(){
        $newX = $this->_currentX;
        $newY = $this->_currentY;
            switch ($this->_currentAngle){
                case 0:
                    $newX--;
                    break;
                case 90:
                    $newY--;
                    break;
                case 180:
                    $newX++;
                    break;
                case 270:
                    $newY++;
                    break;
            }
            return $this->_checkMove($newX, $newY, $this->_currentAngle);
        }
    public function goBack(){
        if ($this->checkBack() == true){
            switch ($this->_currentAngle){
                case 0:
                    $this->_currentX--;
                    break;
                case 90:
                    $this->_currentY--;
                    break;
                case 180:
                    $this->_currentX++;
                    break;
                case 270:
                    $this->_currentY++;
                    break;
            }
        }
    }
    
    public function checkRight(){
        $newX = $this->_currentX;
        $newY = $this->_currentY;
        if ($this->_checkMove($newX, $newY, $this->_currentAngle) == true){
            switch ($this->_currentAngle){
                case 0:
                    $newY--;
                    break;
                case 90:
                    $newX++;
                    break;
                case 180:
                    $newY++;
                    break;
                case 270:
                    $newX--;
                    break;
            }
            return $this->_checkMove($newX, $newY, $this->_currentAngle);
        }else{
            return false;
        }
    }
    public function goRight(){
        if ($this->checkRight() == true){
            switch ($this->_currentAngle){
                case 0:
                    $this->_currentY--;
                    break;
                case 90:
                    $this->_currentX++;
                    break;
                case 180:
                    $this->_currentY++;
                    break;
                case 270:
                    $this->_currentX--;
                    break;
            }
        }
    }

    public function checkLeft(){
        $newX = $this->_currentX;
        $newY = $this->_currentY;
        if ($this->_checkMove($newX, $newY, $this->_currentAngle) == true){
            switch ($this->_currentAngle){
                case 0:
                    $newY++;
                    break;
                case 90:
                    $newX--;
                    break;
                case 180:
                    $newY--;
                    break;
                case 270:
                    $newX++;
                    break;
            }
            return $this->_checkMove($newX, $newY, $this->_currentAngle);
        }else{
            return false;
        }
    }
    public function goLeft(){
        if ($this->checkLeft() == true){
            switch ($this->_currentAngle){
                case 0:
                    $this->_currentY++;
                    break;
                case 90:
                    $this->_currentX--;
                    break;
                case 180:
                    $this->_currentY--;
                    break;
                case 270:
                    $this->_currentX++;
                    break;
            }
        }
    }

    public function goTurnRight(){
            switch ($this->_currentAngle){
                case 0:
                    $this->_currentAngle = 270;
                    break;
                case 90:
                    $this->_currentAngle = 0;
                    break;
                case 180:
                    $this->_currentAngle = 90;
                    break;
                case 270:
                    $this->_currentAngle = 180;
                    break;
                }
        }
    
    public function goTurnLeft(){
            switch ($this->_currentAngle){
                case 0:
                    $this->_currentAngle = 90;
                    break;
                case 90:
                    $this->_currentAngle = 180;
                    break;
                case 180:
                    $this->_currentAngle = 270;
                    break;
                case 270:
                    $this->_currentAngle = 0;
                    break;
            }
        }

         // Réinitialise le tableau _SESSION['inventory'], les status des actions aussi et retourne une popup de victoire
    public function reset(){
        unset($_SESSION['cle_dore']);
        
        $sql = "UPDATE action SET status=0";
        $query = $this->_dbh->prepare($sql);
        $query->execute();

        $popup = 
        '<div class="popup" id="popup">
        <div class="popup-back"></div>
        <div class="popup-container" id="background-victoire">
            <h1>Victoire !!</h1>
            <p>
                Vous avez trouver la clé et vous êtes sorti ! Bravo !<br>
                Voulez-vous recommencé ?
            </p>
            <br>
            <form method="post">
                <input type="submit" name="oui" class="btnOui" value="Oui !"></input>
                <input type="submit" name="non" class="btnNon" value="Non"></input>
            </form>
        </div>
    </div>';
    return $popup;
    }
}