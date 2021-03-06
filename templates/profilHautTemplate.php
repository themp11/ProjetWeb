
<div id="profilHaut">
			<div class="row well ">
				<div class="col-md-12 cov">
						<div class="panel" style="background-image:url(<?php echo($photoC)?>);">
							<div class="name">
								<span class="bjr-mess"><strong> <?php echo $Pseudo ?></strong></span>
								<?php
									if($me){
										echo '<button id="chg-cov" type="button" class="btn pull-right" data-toggle="modal" data-target="#changePS"><span class="glyphicon glyphicon-picture"></span> Changer pasword</button>';
										echo '<button id="chg-cov" type="button" class="btn pull-right" data-toggle="modal" data-target="#coverSelector"><span class="glyphicon glyphicon-picture"></span> Changer cover</button>';
									}
								?>
								<ul class="pull-right">
								  <li><span data-toggle="tooltip" title="Parties jouées" data-placement="bottom" class="glyphicon glyphicon-king icon"></span><span><?php echo $partieT ?></span></li>
								  <li><span data-toggle="tooltip" title="Parties gagnées" data-placement="bottom" class="glyphicon glyphicon-thumbs-up icon"></span><span><?php echo $partieG ?></span></li>
								  <li><span data-toggle="tooltip" title="Parties perdues" data-placement="bottom" class="glyphicon glyphicon-thumbs-down icon"></span><span><?php echo $partieP ?></span></li>
								  <li><span data-toggle="tooltip" title="Rapport parties gagnées/jouées" data-placement="bottom" class="glyphicon glyphicon-stats icon"></span><span><?php echo number_format($averageWin, 2, '.', ',')*100 ?>%</span></li>
								</ul>
							</div>
							<?php
								if($me){
									echo '<img data-toggle="modal" data-target="#profilSelector" class="pic img-circle" alt="..." src="'.$photoP.'" />';
								}
								else{
									echo '<img class="pic img-circle" alt="..." src="'.$photoP.'" />';
								}
							?>
						</div>
			 </div>
			</div>

			<div id="coverSelector" class="modal fade" role="dialog">
				<div class="modal-dialog modal-sm popup">
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal">&times;</button>
				        <h4 class="modal-title">Changer la photo de couverture</h4>
				      </div>
				      <div class="modal-body">
									<form id="popUpForm" class="form-horizontal well" method="post" action="index.php" enctype="multipart/form-data">
                            <div class="form-group">
                                <div class="col-lg-10">
                                	<input type="hidden" name="action" value="uploadPhotoCover" />
                                	<input type="hidden" name="MAX_FILE_SIZE" value="1000000000" />
                                    <input type="file" class="form-control" name="image" accept="image/*">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-10 col-lg-offset-2">
                                    <button type="submit" class="btn btn-primary pull-right">Envoyer</button>
                                </div>
                            </div>
                        </fieldset>
                    </form>
				      </div>
				      </div>
				    </div>
				  </div>
			</div>

			<div id="profilSelector" class="modal fade" role="dialog">
				<div class="modal-dialog modal-sm popup">
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal">&times;</button>
				        <h4 class="modal-title">Changer la photo de profil</h4>
				      </div>
				      <div class="modal-body">
									<form id="popUpForm" class="form-horizontal well" method="post" action="index.php" enctype="multipart/form-data">
                            <div class="form-group">
                                <div class="col-lg-10">
                                	<input type="hidden" name="action" value="uploadPhotoProfil" />
                                	<input type="hidden" name="MAX_FILE_SIZE" value="1000000000" />
                                    <input type="file" class="form-control" name="image" accept="image/*">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-10 col-lg-offset-2">
                                    <button type="submit" class="btn btn-primary pull-right">Envoyer</button>
                                </div>
                            </div>
                        </fieldset>
                    </form>
				      </div>
				      </div>
				    </div>
				  </div>
			</div>

			<div id="changePS" class="modal fade" role="dialog">
				<div class="modal-dialog modal-sm popup">
				    <div class="modal-content">
				      	<div class="modal-header">
				        	<button type="button" class="close" data-dismiss="modal">&times;</button>
				        	<h4 class="modal-title">Changer le mots de passe</h4>
				      	</div>
				      	<div class="modal-body">
                            <form id="popUpForm" role="form" data-toggle="validator" class="form-horizontal well" action="index.php" method="post" enctype="multipart/form-data">
								<fieldset>
									<input type="hidden" name="action" value="changePassword" />
									<div class="form-group">
										<label class="col-sm-6 control-label "><FONT COLOR="black" >Ancien mot de passe </FONT><span>*</span></label>
										<div class="col-sm-6">
									  		<input type="password" style="color: black" name="oldPassword" id="test" required/>
										</div>
									</div>
								
									<div class="form-group">
										<label class="col-sm-6 control-label"><FONT COLOR="black" >Nouveau mot de passe </FONT><span>*</span></label>
										<div class="col-sm-6">
										  <input type="password" name="newPassword1" style="color: black" required/>
										</div>
									</div>
								
									<div class="form-group">
										<label class="col-sm-6 control-label"><FONT COLOR="black" >Nouveau mot de passe </FONT><span>*</span></label>
										<div class="col-sm-6">
										  <input  type="password" name="newPassword2" style="color: black" required/>
										</div>
									</div>
								
									<div class="form-group">
										<button class="boutonMenu bouton hvr-grow col-sm-offset-5" id="bouton" type="submit" >Changer</Button>
									</div>
								</fieldset>
							</form>
				      	</div>
				    </div>
				</div>
			</div>
		</div>
