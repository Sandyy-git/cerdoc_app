
   <meta name="google-signin-client_id" content="446842558210-e0n6rfkcvinclkcud7lgncnauttcmpr1.apps.googleusercontent.com">

 <!-- <script src="https://apis.google.com/js/platform.js" async defer></script> -->
 <script src="https://accounts.google.com/gsi/client" async defer></script>


  <style>
.center{
   position:absolute;
   left:50%;
   top:50%;
   transform:translate(-50%, -50%);

   color: #cd8d42;
    font-size: xx-large;

    box-sizing: border-box;
  -moz-box-sizing: border-box;
  -webkit-box-sizing: border-box;
  width: 300px;
  height: 300px;
  border: 2px solid #969696;
  background: #d9dbda;
  margin: 2px;
  text-align: center;   
}
</style>

<div class="content-wrapper ">
<div class="card bg-dark text-white center">  
<div class="card-body" style="margin-top: 30%;">
<!-- <div class="g-signin2" data-onsuccess="onSignIn"></div> -->

<div id="g_id_onload"
         data-client_id="446842558210-e0n6rfkcvinclkcud7lgncnauttcmpr1.apps.googleusercontent.com"
         data-callback="handleCredentialResponse">
    </div>
    <div class="g_id_signin" data-type="standard"></div>

                                
</div>
</div>
</div>

<script>
function handleCredentialResponse(response) {
     // decodeJwtResponse() is a custom function defined by you
     // to decode the credential response.
    //  console.log(response);
     console.log(response.credential);
    //  const responsePayload = decodeJwtResponse(response.credential);
    const decode =  decodeURIComponent(atob(response.credential.split('.')[1].replace('-', '+').replace('_', '/')).split('').map(c => `%${('00' + c.charCodeAt(0).toString(16)).slice(-2)}`).join(''));
    console.log(decode);
    const parseDecode = JSON.parse(decode);

     console.log("ID: " + parseDecode.sub);
     console.log('Full Name: ' + parseDecode.name);
     console.log('Given Name: ' + parseDecode.given_name);
     console.log('Family Name: ' + parseDecode.family_name);
     console.log("Image URL: " + parseDecode.picture);
     console.log("Email: " + parseDecode.email);
  }
</script>
