
   <?=$render('header', ['loggedUser'=>$loggedUser]); ?>
    <section class="container main">
        <?=$render('sidebar',['activeMenu'=>'config']);  ?>  
                    
                <form method="POST" action="<?=$base;?>/config">
                    <?php  if(!empty($flash)): ?>
                        <div class="flash"> <?php echo $flash; ?> </div>
                    <?php endif; ?>
                    
                    Nome Completo:  <br/>
                    <input placeholder="<?=$loggedUser->name;?>" class="input" type="text" name="name" /><br/>    
                    Senha:  <br/>    
                    <input placeholder="*********" class="input" type="password" name="password" /><br/>  
                    Data de Nascimento: <br/>      
                    <input placeholder="<?=$loggedUser->birthdate;?>" class="input" type="text" name="birthdate" id="birthdate" /><br/>      
                    Cidade: <br/>      
                    <input placeholder="<?=$loggedUser->city;?>" class="input" type="text" name="city" /><br/>  
                    Local de Trabalho <br/>      
                    <input placeholder="<?=$loggedUser->work;?>" class="input" type="text" name="work" /><br/> <br/>   

                    <input class="button" type="submit" value="Atualizar" />
                </form>
           
    </section>
<?=$render('footer');?> 

<script src="https://unpkg.com/imask"></script>
<script> 
IMask(
    document.getElementById('birthdate'),
    {
        mask:'00/00/0000'
    }    
);
</script>


