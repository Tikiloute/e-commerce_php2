<h1>Modifier le rôle</h1>

<h4>Vous souhaitez modifier le rôle d'une personne déjà inscrite sur votre site afin qu'il/elle puisse accèder à l'espace administrateur ? Ou au contraire remettre en rôle client un administrateur ?</h4>

<hp>Indiquez son identifiant puis choisissez son rôle :</p>

<form action="<?= URL ?>connect/modifyRoleValidate" method="POST">
<input type="text" name="login" id="">
<select name="role" id="">
<option value="" selected disabled>Choisissez le rôle </option>
    <option value="client">Client</option>
    <option value="administrateur">Administrateur</option>
</select>
<input class="btn btn-primary" type="submit" value="Validez">
</form>