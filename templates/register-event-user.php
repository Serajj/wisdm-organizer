<style>
.wsdm-event-user-form input[type=text], select , input[type=email] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

.wsdm-event-user-form input[type=submit] {
  width: 100%;
  background-color: #4CAF50;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.wsdm-event-user-form input[type=submit]:hover {
  background-color: #45a049;
}

.wsdm-event-user-form{
  border-radius: 5px;
  background-color: #f2f2f2;
  padding: 20px;
  width:50%
}
</style>
<center>
<div class="wsdm-event-user-form">
  <form method="POST">
    <label for="fname">Full Name</label>
    <input type="text" id="fname" name="title" placeholder="Your name..">

    <label for="lname">Email</label>
    <input type="email" id="lname" name="email" placeholder="Your Email..">

    <label for="spci">Speciality</label>
    <input type="text" id="spci" name="specification" placeholder="Coding,Comtent Writing , Marketing">
    
    <input type="hidden" name="action" value="teammember" />
    <input type="submit" value="Submit">
  </form>
</div>
</center>