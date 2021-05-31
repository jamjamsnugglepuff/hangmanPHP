<?php




class Game{
  public $player, $tip, $guesses, $lives, $wordIdx, $randomWord, $running;

  function save_game(){
    $data = json_encode($this);
    $file = fopen('save.json','w');
    $x = fwrite($file, $data) ? true : false; 
    fclose($file);
    echo $x == true ? "Game saved!" : "Error saving game";
  }

  function load_game(){
    $file = file_get_contents('save.json');
    $datax = json_decode($file);
    $this->player = $datax->player;
    $this->tip = $datax->tip;
    $this->guesses = $datax->guesses;
    $this->lives = $datax->lives;
    $this->randomWord = $datax->randomWord;
    $this->running = $datax->running;
  }


  function setup_game(){
    $name = readline("Welcome to hangman player what is your name?\n");
    $this->player = new Player($name);
    $this->running = true;
    $this->randomWord = $this->select_word();
    $this->tip = $this->generate_tip(strlen($this->randomWord));
    echo $this->randomWord . "\n";
    // echo $this->save_game();  
    // echo $this->load_game();
    $this->run_game();   
  }

  function generate_tip($n){
    $tip =[];
    for($i = 0 ; $i < $n ; $i ++){
      $tip[] = "_";
    }
    return  $tip;
  }

  function select_word(){
    // open file
    $file = fopen('5desk.txt', 'r');
    $rows = 0;
    while(!feof($file)){
      $line = fgets($file);
      $rows ++;
    }
    fclose($file);
    $file = file('5desk.txt');
    $random = rand(0, $rows);
    $this->wordIdx = $random;
    return trim($file[$random]);
  }

  function run_game(){
    $this->lives = 10;
    while($this->running == true){
      echo "Player would you like to load previous game enter: y/n\n";
      $load_game = readline("Input:");
      if($load_game == 'y'){
        $this->load_game();
      }
      echo "Player would you like to save game Enter: y/n\n";
      $save_game = readline("Input:");

      if($save_game == 'y'){
        $this->save_game();
      }

      echo "Lives Remaining : " . $this->lives . "\n";
      $this->display_board();
  // while game is running
    // ask player to input a character
      echo "player input 1 charcter\n";
      $guess = readline("Input:");
      
    // if word has that character
      if($this->detect_character($guess)){
        // display that letter
        echo "Wow good guess!\n";
        if($this->tip == str_split($this->randomWord)){
          echo "WOW GOOD WORK GAME OVER YOU WIN ! :)\n";
          $this->running = false;
        }
      }else{
        echo "BRrrrrrrrrp Err ewww bad luck player!\n";
        $this->lives --;
        if ($this->lives == 0){
          $this->running = false;
          echo "Brrrup game over >:( !"; 
        }
      }
    }
  }

  function detect_character($char){
    $word = $this->randomWord;
    $found = false;
    foreach(str_split($word) as $idx=>$value){
      if($value == $char){
        $found = true;
        $this->tip[$idx] = $value;
      }
    }
    return $found == true;
  }

  function display_board(){
    foreach($this->tip as $tip){
      echo $tip . ' ' ;
    }

    echo "\n";
  }


}

class Player{
  public $name;

  function __construct($name){
    $this->name = $name;
  }
}


$game = new Game();
$game->setup_game();