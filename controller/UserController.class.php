<?php
	class UserController extends Controller
	{
		public function __construct($req) {

		}

		public function defaultAction($arg) {
			$view = new UserView($this,"AccueilConnected");

			//------------------
			// demande d'amis + demande joindre partie
			//------------------
			$evenementFriend = Friends::Evenement_FriendAdding($arg->read('id'));
			$evenementGame = WaitingRoom::Evenement_FriendAddingInGame($arg->read('id'));

			$view->setArg("Pseudo", $arg->read('user'));
			$view->setArg("photoP", $arg->read('photoP'));
			$view->setArg("evenementFriend", $evenementFriend);
			$view->setArg("evenementGame", $evenementGame);

			$view->render();
		}

		public function logout($arg) {
			//unset($_COOKIE["user"]);
			setcookie ("user", "", time() - 3600);
			setcookie ("partieG", "", time() - 3600);
			setcookie ("partieP", "", time() - 3600);
			setcookie ("partieT", "", time() - 3600);
			setcookie ("averageWin", "", time() - 3600);
			setcookie ("photoP", "", time() - 3600);
			setcookie ("photoC", "", time() - 3600);
			setcookie ("afficheAmis", "", time() - 3600);
			setcookie ("id", "", time() - 3600);
			setcookie("gameName",time() - 3600);
			setcookie("controller","anonymous", time()+ 3600*24);

			$view = new AnonymousView($this,"home");
			$view->render();
		}

		public function showProfil($arg) {
			$view = new UserProfilView($this,"profilHaut");

			$rank = User::getPlayerRanked();
			$history = User::getHistorique($arg->read('id'));

			$view->setArg("me", TRUE);
			$view->setArg("history", $history);
			$view->setArg("rank", $rank);
			$view->setArg("Pseudo", $arg->read('user'));
			$view->setArg("photoC", $arg->read('photoC'));
			$view->setArg("photoP", $arg->read('photoP'));
			$view->setArg("partieT", $arg->read('partieT'));
			$view->setArg("partieG", $arg->read('partieG'));
			$view->setArg("partieP", $arg->read('partieP'));
			$view->setArg("averageWin", $arg->read('averageWin'));

			$view->render();
		}

		public function showFriendProfil($arg) {
			$friend = Friends::getAFriend($arg->read('friend'));

			$view = new FriendProfilView($this,"profilHaut");
			if($friend != NULL){
				$rank = User::getPlayerRanked();
				$history = User::getHistorique($friend->IDJOUEUR);

				$view->setArg("me", FALSE);
				$view->setArg("rank", $rank);
				$view->setArg("history", $history);
				$view->setArg("Pseudo", $friend->PSEUDO);
				$view->setArg("photoC",  $friend->PHOTOCOVER);
				$view->setArg("photoP",  $friend->PHOTOPROFIL);
				$view->setArg("partieT",  $friend->NBRPARTIEJOUEE);
				$view->setArg("partieG",  $friend->NBRPARTIEGAGNEE);
				$view->setArg("partieP",  $friend->NBRPARTIEJOUEE - $friend->NBRPARTIEGAGNEE);
				if($friend->NBRPARTIEJOUEE ==0){
						$view->setArg("averageWin",  0);
				}
				else{
						$view->setArg("averageWin",  $friend->NBRPARTIEGAGNEE/$friend->NBRPARTIEJOUEE);
				}
			}
			else{
				$view = new UserView($this,"AccueilConnected");
				$evenementFriend = Friends::Evenement_FriendAdding($arg->read('id'));
				$view->setArg("evenementFriend", $evenementFriend);
				$view->setArg("Pseudo", $arg->read('user'));
				$view->setArg("photoP", $arg->read('photoP'));
			}

			$view->render();
		}

		public function showFriends($arg) {
			$view = new UserFriendsView($this,"profilFriends");

			$friends = Friends::getFriends($arg->read('id'));
			$friendGame = ListePartie::getFriendGame($arg->read('id'));
			$view->setArg("friends", $friends);
			$view->setArg("friendGame", $friendGame);

			$view->setArg("Pseudo", $arg->read('user'));
			$view->setArg("photoP", $arg->read('photoP'));

			$view->render();
		}

		public function creatGame($arg) {
			$view = new CreatGameView($this,"creatGame");

			$view->render();
		}

		public function joinGame($arg) {
			$view = new JoinGameView($this,"joinGame");

			$nbrTemp = NULL;

			//partie publique
			$publicGame = ListePartie::getPartiePublic($arg->read('id'));
			if($publicGame != NULL){
				$nbrPublicGame = array();
				foreach ($publicGame as $pg) {
					$nbrTemp = Game::NumberOfParticipant($pg->NOMPARTIE);
					array_push($nbrPublicGame, $nbrTemp);
				}
				$view->setArg("nbrPublicGame", $nbrPublicGame);
			}
			$view->setArg("publicGame", $publicGame);

			//partie en tant que participant
			$userGame = ListePartie::getParticipantGame($arg->read('id'));
			if($userGame != NULL){
				$nbrUserGame = array();
				foreach ($userGame as $ug) {
					$nbrTemp = Game::NumberOfParticipant($ug->NOMPARTIE);
					array_push($nbrUserGame, $nbrTemp);
				}
				$view->setArg("nbrUserGame", $nbrUserGame);
			}
			$view->setArg("userGame", $userGame);

			//partie en tant que créateur
			$ownerGame = ListePartie::getOwnerGame($arg->read('id'));
			if($ownerGame != NULL){
				$nbrOwnerGame = array();
				foreach ($ownerGame as $og) {
					$nbrTemp = Game::NumberOfParticipant($og->NOMPARTIE);
					array_push($nbrOwnerGame, $nbrTemp);
				}
				$view->setArg("nbrOwnerGame", $nbrOwnerGame);
			}
			$view->setArg("ownerGame", $ownerGame);

			$view->render();

		}

		public function continueGame($arg) {
			$view = new ContinueGameView($this,"continueGame");

			$startGame = ListePartie::getPartieStarted($arg->read('id'));
			if($startGame != NULL){
				$nbrUserGame = array();
				foreach ($startGame as $ug) {
					$nbrTemp = Game::NumberOfParticipant($ug->NOMPARTIE);
					array_push($nbrUserGame, $nbrTemp);
				}
				$view->setArg("nbrUserGame", $nbrUserGame);
			}
			$view->setArg("startGame", $startGame);

			$view->render();
		}

		public function validateGameCreation($args){ //TODO redirection vrs la bonne salle : pas le cas ici
			$name = $args->read('name');
			if($name == NULL ){
				$view = new CreatGameView($this,"creatGame");
				$view->setArg('inscErrorText','Veuillez insérer un nom de partie');
				$view->render();
			}
			else if(ListePartie::isGameNameUsed($name) == true){
				$view = new CreatGameView($this,"creatGame");
				$view->setArg('inscErrorText','Nom de partie déjà utilisé');
				$view->render();
			}
			else{
				$isPublic = $args->read('public');
				if($isPublic == NULL){
					$isPublic = 0;
				}
				else{
					$isPublic = 1;
				}
				$args->write('gameName',$name);
				ListePartie::creatGame($args->read('id'), $isPublic , 0 , $name);
				ListePartie::AddPlayer($args->read('id'),$name);
				$this->goWaitingRoom($args);

			}
		}
		public function deleteFriend($args){
			Friends::RemoveFriendP1($args->read('id'), $args->read('friend'));
			Friends::RemoveFriendP2($args->read('id'), $args->read('friend'));
			$this->showFriends($args);
		}

		public function goWaitingRoom($arg) {
			$gameName = $arg->read('gameName');
			$number = WaitingRoom::NumberOfParticipant($gameName);
			$started = WaitingRoom::getEnCours($gameName);

			$creator = WaitingRoom::getGameCreator($gameName);
			$participants= WaitingRoom::getParticipant($gameName);
			$isParticipant = false;
			if($participants != NULL){
				foreach ($participants as $participant){
					if($participant->PSEUDO === $arg->read('user')){
						$isParticipant = true;
					}
				}
			}

			if($creator != NULL && $creator->PSEUDO === $arg->read('user')){
				if( !$started){
					$view = new WaitingRoomView($this,"waitingRoomBasCreator");
					$public= WaitingRoom::isPublic($gameName);

					$view->setArg("number", $number);
					$view->setArg("creator", $creator);
					$view->setArg("gameName", $gameName);
					$view->setArg("participant", $participants);
					$view->setArg("public", $public);

					$view->render();
				}
				else{
					$this->startGame($arg);
				}
			}
			else if($isParticipant){
				if( !$started){
					$view = new WaitingRoomView($this,"waitingRoomBasParticipant");
					$public= WaitingRoom::isPublic($gameName);

					$view->setArg("creator", $creator);
					$view->setArg("number", $number);
					$view->setArg("gameName", $gameName);
					$view->setArg("participant", $participants);
					$view->setArg("public", $public);

					$view->render();
				}
				else{
					$this->startGame($arg);
				}
			}
			else{
				$this->defaultAction($arg);
			}
		}

		public function changePublic($arg){
			$isPublic = WaitingRoom::isPublic($arg->read('gameName'));
			if($isPublic){
				WaitingRoom::putPrivate($arg->read('gameName'));
			}
			else{
				WaitingRoom::putPublic($arg->read('gameName'));
			}
			$this->goWaitingRoom($arg);
		}





		//--------------------------------------------------------
		// Gestion de la demande d'ami et acceptation/refus
		//--------------------------------------------------------
		public function showFriendAsking($arg){
			$view = new UserFriendsView($this,"evenementFriendAsking");

			$friendsAsking = Friends::Evenement_FriendAdding($arg->read('id'));

			$view->setArg("friendsAsking", $friendsAsking);

			$view->setArg("Pseudo", $arg->read('user'));
			$view->setArg("photoP", $arg->read('photoP'));

			$view->render();
		}

		public function accepetFriend($arg){
			Friends::AddFriend($arg->read('id'), $arg->read('friend'));
			$this->defaultAction($arg);
		}

		public function refuseFriend($args){
			Friends::RemoveFriendP1($args->read('id'), $args->read('friend'));
			Friends::RemoveFriendP2($args->read('id'), $args->read('friend'));
			$this->defaultAction($arg);
		}

		public function addFriend($arg){
			$view = new UserFriendsView($this,"profilFriends");

			$friends = Friends::getFriends($arg->read('id'));
			$friendGame = ListePartie::getFriendGame($arg->read('id'));
			$view->setArg("friends", $friends);
			$view->setArg("friendGame", $friendGame);

			$view->setArg("Pseudo", $arg->read('user'));
			$view->setArg("photoP", $arg->read('photoP'));

			if($arg->read('user') === $arg->read('friendName')){
				$view->setArg('inscErrorText',"Vous ne pouvez pas vous ajouter en ami");
			}
			else{
				$userExist = Friends::userExist($arg->read('friendName'));
				if($userExist){
					$try = Friends::AskFriend($arg->read('id'), $arg->read('friendName'));
					if($try){
						$view->setArg('inscOKText',"demande d'ami envoyé");
					}

					else{
						if(Friends::isFriend($arg->read('id'), $arg->read('friendName'))){
							$view->setArg('inscErrorText',"Vous êtes déjà ami avec ". $arg->read('friendName'));
						}
						else{
							$view->setArg('inscErrorText',"demande d'ami déjà envoyé à ce joueur");
						}
					}
				}
				else{
					$view->setArg('inscErrorText',"le joueur n'existe pas");
				}
			}

			$view->render();
		}





		//--------------------------------------------------------
		//Méthode pour la gestion d'invitation dans une partie
		//--------------------------------------------------------
		public function addFriendInGame($arg){
			$gameName = $arg->read('gameName');

			$creator = WaitingRoom::getGameCreator($gameName);
			$participants= WaitingRoom::getParticipant($gameName);
			$friends = Friends::getFriends($arg->read('id'));

			$view = new WaitingRoomAddingFriendView($this,"waitingRoomBasAddingFriend");
			$public= WaitingRoom::isPublic($gameName);

			$view->setArg("friends", $friends);
			$view->setArg("creator", $creator);
			$view->setArg("gameName", $gameName);
			$view->setArg("participant", $participants);
			$view->setArg("public", $public);
			$view->render();
		}

		public function addInGame($arg){

			$inGameAsking = waitingRoom::isFriendInGameAsking($arg->read('gameName'), $arg->read('friend'));
			$inGame = waitingRoom::isFriendInGame($arg->read('gameName'), $arg->read('friend'));

			$arg->write('gameName',$arg->read('gameName'));
			setcookie("gameName",$arg->read('gameName'), time()+ 3600*24);
			$gameName = $arg->read('gameName');

			$creator = WaitingRoom::getGameCreator($gameName);
			$participants= WaitingRoom::getParticipant($gameName);
			$friends = Friends::getFriends($arg->read('id'));
			$number = WaitingRoom::NumberOfParticipant($arg->read('gameName'));

			$view = new WaitingRoomView($this,"waitingRoomBasCreator");
			$public= WaitingRoom::isPublic($gameName);

			$view->setArg("number", $number);
			$view->setArg("friends", $friends);
			$view->setArg("creator", $creator);
			$view->setArg("gameName", $gameName);
			$view->setArg("participant", $participants);
			$view->setArg("public", $public);

			if($inGameAsking){
				$view->setArg("inscErrorFull", "invitation déjà envoyé à cet ami");
				$view->render();

			}
			else if($inGame){
				$view->setArg("inscErrorFull", "ami déjà dans la partie");
				$view->render();
			}
			else{
			waitingRoom::AskFriendInGame($arg->read('gameName'), $arg->read('friend'));
				$view->setArg("inscOKFull", "invitation envoyée");
				$view->render();
			}
		}

		public function evenementGame($arg){
			$view = new UserFriendsView($this,"evenementFriendAskingInGame");

			$joinedGame = WaitingRoom::Evenement_FriendAddingInGame($arg->read('id'));

			$view->setArg("joinedGame", $joinedGame);

			$view->setArg("Pseudo", $arg->read('user'));
			$view->setArg("photoP", $arg->read('photoP'));

			$view->render();
		}

		public function acceptGame($arg){
			$number = WaitingRoom::NumberOfParticipant($arg->read('game'));
			if($number > 10){
				WaitingRoom::removeInvit($arg->read('game'), $arg->read('user'));
				$view = new UserView($this,"AccueilConnected");

				//------------------
				// demande d'amis + demande joindre partie
				//------------------
				$evenementFriend = Friends::Evenement_FriendAdding($arg->read('id'));
				$evenementGame = WaitingRoom::Evenement_FriendAddingInGame($arg->read('id'));

				$view->setArg("Pseudo", $arg->read('user'));
				$view->setArg("photoP", $arg->read('photoP'));
				$view->setArg("evenementFriend", $evenementFriend);
				$view->setArg("evenementGame", $evenementGame);
				$view->setArg("inscErrorFull", "La salle est pleine");

				$view->render();
			}
			else{
				WaitingRoom::AddInGame($arg->read('game'), $arg->read('user'));
				$arg->write("gameName",$arg->read('game'));
				$this->goWaitingRoom($arg);
			}
		}

		public function refuseGame($arg){
			WaitingRoom::removeInvit($arg->read('game'), $arg->read('user'));
				$view = new UserView($this,"AccueilConnected");

				//-----------------------------------------------------
				// demande d'amis + demande joindre partie
				//-----------------------------------------------------
				$evenementFriend = Friends::Evenement_FriendAdding($arg->read('id'));
				$evenementGame = WaitingRoom::Evenement_FriendAddingInGame($arg->read('id'));

				$view->setArg("Pseudo", $arg->read('user'));
				$view->setArg("photoP", $arg->read('photoP'));
				$view->setArg("evenementFriend", $evenementFriend);
				$view->setArg("evenementGame", $evenementGame);

				$view->render();
		}

		public function rank($arg){
				$view = new RankView($this,"rank");
				$rank = User::getPlayerRanked();

				$view->setArg("rank", $rank);
				$view->render();
		}








		//-----------------------------------------------------
		//Lance la partie. Changement de user -> gameController
		//-----------------------------------------------------
		public function startGame($arg){
			$gameName = $arg->read('gameName');
			$participants= WaitingRoom::getParticipant($gameName);

			$isParticipant = false;
			foreach ($participants as $participant){
				if($participant->PSEUDO === $arg->read('user')){
					$isParticipant = true;
				}
			}
			if($isParticipant){
				$newRequest = Request::getCurrentRequest();
				$newRequest->write('controller','game');
				$newRequest->write('action','Anonymous');
				setcookie("controller","game", time()+ 3600*24);
				setcookie("gameName",$gameName, time()+ 3600*24);
				try {
					// Instantiate the adequat controller according to the current request
					$controller = Dispatcher::dispatch($newRequest);

					// Execute the requested action
					$controller->execute();
				} catch (Exception $e) {
					echo 'Error : ' . $e->getMessage() . "\n";
				}
			}
			else{
				$this->defaultAction($arg);
			}
		}

		public function lauchGame($arg){
			$gameName = $arg->read("gameName");
			$number = WaitingRoom::NumberOfParticipant($gameName);

			if($number > 10 || $number < 2){
				$this->goWaitingRoom($arg);
			}
			else{
				WaitingRoom::putPrivate($gameName);

				// supprime toutes les invitations
				WaitingRoom::deleteAllInvit($gameName);

				//crée une main pour chaque participant
				$participants= WaitingRoom::getParticipant($gameName);
				foreach ($participants as $p) {
					WaitingRoom::createHand($gameName, $p->PSEUDO);
				}

				//ajoute 10 cartes par main
				$input = array();
				for ($i = 1; $i <= 104; $i++) {
					array_push($input, $i);
				}
				shuffle($input);

				$j = 0;
				foreach ($participants as $p) {
					for ($i = 1; $i <= 10; $i++) {
						WaitingRoom::addCardOnHand($gameName, $p->PSEUDO, $input[$j] );
						$j = $j + 1;
					}
				}

				//place les 4 cartes de départ sur le plateau
				Game::addCardOnGame($input[$j], 1, $gameName, 1 );
				$j = $j + 1;
				Game::addCardOnGame($input[$j], 2, $gameName, 1 );
				$j = $j + 1;
				Game::addCardOnGame($input[$j], 3, $gameName, 1 );
				$j = $j + 1;
				Game::addCardOnGame($input[$j], 4, $gameName, 1 );


				WaitingRoom::putEnCours($gameName);
				$this->startGame($arg);

			}
		}


		public function uploadPhotoCover($arg){
			$uploaddir = './photo/';
			$_FILES['image']['name']= $arg->read('user') .'-imgCover.png';

			$uploadfile = $uploaddir . basename($_FILES['image']['name']);
			User::setPhotoCover($arg->read('id'), 'photo/'. $arg->read('user') .'-imgCover.png');

			setcookie("photoC",'photo/'. $arg->read('user') .'-imgCover.png', time()+ 3600*24);
			$arg->write('photoC','photo/'. $arg->read('user') .'-imgCover.png');

			$this->showProfil($arg);

			if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
			    //fichier téléchargé avec succès
			} else {
			   //attaque potentiel par téléchargement de fichier
			}
		}

		public function uploadPhotoProfil($arg){
			$uploaddir = './photo/';
			$_FILES['image']['name']= $arg->read('user') .'-imgPicture.png';

			$uploadfile = $uploaddir . basename($_FILES['image']['name']);
			User::setPhotoProfil($arg->read('id'), 'photo/'. $arg->read('user') .'-imgPicture.png');

			setcookie("photoP",'photo/'. $arg->read('user') .'-imgPicture.png', time()+ 3600*24);
			$arg->write('photoP','photo/'. $arg->read('user') .'-imgPicture.png');

			$this->showProfil($arg);

			if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
			    //fichier téléchargé avec succès
			} else {
			   //attaque potentiel par téléchargement de fichier
			}
		}

		public function changePassword($arg){
			$oldPassword = $arg->read('oldPassword');
			$newPassword1 = $arg->read('newPassword1');
			$newPassword2 = $arg->read('newPassword2');

			$view = new UserProfilView($this,"profilHaut");
			$rank = User::getPlayerRanked();
			$view->setArg("me", TRUE);
			$view->setArg("rank", $rank);
			$view->setArg("Pseudo", $arg->read('user'));
			$view->setArg("photoC", $arg->read('photoC'));
			$view->setArg("photoP", $arg->read('photoP'));
			$view->setArg("partieT", $arg->read('partieT'));
			$view->setArg("partieG", $arg->read('partieG'));
			$view->setArg("partieP", $arg->read('partieP'));
			$view->setArg("averageWin", $arg->read('averageWin'));		

			if($oldPassword == NULL || $newPassword1 == NULL || $newPassword2 == NULL){
				$view->setArg('inscErrorText','Il manque des données, veuillez renseigner tous les champs');
			}
			else{
				$user = User::getUser($arg->read("user"), $oldPassword);
				if($user == NULL ){
					$view->setArg('inscErrorText','Ancien mot de passe erroné, veuillez insérer le bon mot de passe');
				}
				else{
					if($newPassword1 !== $newPassword2){
						$view->setArg('inscErrorText','Veuillez saisir le nouveau mot de passe de manière identique');
					}
					else{
						User::changePassword($arg->read("id"), $oldPassword, $newPassword1);
						$view->setArg('inscOkText','Mots de passe changer avec succès');
					}
				}
			}
			$view->render();
		}





		//--------------------------------------------------------
		//Repere l'action et appelle la méthode adéquate
		//--------------------------------------------------------
		public function execute(){
			$request = Request::getCurrentRequest();
			$action = $request->getActionName();
			if($action === "Anonymous"){
				$this->defaultAction($request);
			}
			else if($action === "logout"){
				$this->logout($request);
			}
			else if($action === "profil"){
				$this->showProfil($request);
			}
			else if($action === "friends"){
				$this->showFriends($request);
			}
			else if($action === "FriendProfil"){
				$this->showFriendProfil($request);
			}
			else if($action === "creatGame"){
				$this->creatGame($request);
			}
			else if($action === "joinGame"){
				$this->joinGame($request);
			}
			else if($action === "continueGame"){
				$this->continueGame($request);
			}
			else if($action === "validateGameCreation"){
				$this->validateGameCreation($request);
			}
			else if($action === "deleteFriend"){
				$this->deleteFriend($request);
			}
			else if($action === "goWaitingRoom"){
				$this->goWaitingRoom($request);
			}
			else if($action === "changePublic"){
				$this->changePublic($request);
			}
			else if($action === "evenementFriend"){
				$this->showFriendAsking($request);
			}
			else if($action === "acceptFriend"){
				$this->accepetFriend($request);
			}
			else if($action === "refuseFriend"){
				$this->refuseFriend($request);
			}
			else if($action === "addFriend"){
				$this->addFriend($request);
			}
			else if($action === "addFriendInGame"){
				$this->addFriendInGame($request);
			}
			else if($action === "addInGame"){
				$this->addInGame($request);
			}
			else if($action === "evenementGame"){
				$this->evenementGame($request);
			}
			else if($action === "acceptGame"){
				$this->acceptGame($request);
			}
			else if($action === "refuseGame"){
				$this->refuseGame($request);
			}
			else if($action === "startGame"){
				$this->startGame($request);
			}
			else if($action === "lauchGame"){
				$this->lauchGame($request);
			}
			else if($action === "uploadPhotoCover"){
				$this->uploadPhotoCover($request);
			}
			else if($action === "uploadPhotoProfil"){
				$this->uploadPhotoProfil($request);
			}
			else if($action === "changePassword"){
				$this->changePassword($request);
			}
			else if($action === "rank"){
				$this->rank($request);
			}
			else{
				$this->defaultAction($request);
			}
		}

	}
?>
