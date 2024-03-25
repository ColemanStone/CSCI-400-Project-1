# CSCI-400-Project-1

<h1>Link to website</h1>
<p>https://ko-turing3.ads.iu.edu/~benearl/Project%201/</p>

<h3>Purpose of the Website</h3>
<p>Our car website has the ability to provide users with a way to have their cars valuated
based on certain criteria that they enter. This is done by allowing users to register
an account, using an email and password, as well as letting them log in and log out.
Once signed in, a user can navigate to the car valuation page, where they will input their
information, and calculations will be made to determine the value of the vehicles. The last
feature of our website is providing a way for users to see the history of all the valuations they have made. Worth noting is that we have no way to send emails for password resetting, so if a user forgets their password, we just provide them with a temporary password that can be used to change the password. </p>

<h3>Design Challenges (Backend)</h3>
<p>The most difficult thing we encountered on the backend side of the building of this website was getting necessary values from both the cars database and the owners_cars database. The solution was to get the vin number from owners_cars, and then use that vin to get make, model, and year of the vehicles in order to display the history. We also had a really hard time streamlining the user experience, and we unfortunately ran out of time to get it working the way we want it to. Users are required to go back a page many times, and forms stay on a page even after filling them out successfully, so the results show up on the bottom of the webpage.</p>

<h3>Design Challenges (Frontend)</h3>
<p>The most difficult part abouy developing the front end was getting all the information to look neat on the screen and readable for the user. And to make the instructions on pages to be clear and readable so the user can nagivate the website without any problem</p>

<h3>Features Missing/Not Fully Functional</h3>
<p>Due to needing unique vin numbers, trying to revaluate a car will not work, as the new car will not be added to the database. The user will occasionally fill out a form and be required to press the previous page (left arrow) button in order to get back to the home page, which will result in the form trying to resubmit. We had issues with checkboxes for the last piece of the form, so we elected to use radio buttons. The problem with that is that only one option can be selected, and if one is selected by mistake, the user can no longer deselect. </p>

<h3>Procedure for Testing</h3>
<p>First, set up dblogin.php to be your information, then change the base url in config.php to be something that works for you. Then copy and paste our database code into putty, doing the owners_cars table last. 
Once on the wesbite with, begin by logging in to the system, as that will allow you to have cars valuated. Then enter information for the vehicle of your choice. We decided to provide drop down menus for the make and model of the cars, so you'll have to select from those lists. Depending on the make of the vehicle selected, only certain models will be available to be chosen. 
After valuating a vehicle, you should be able to press the history button to have it display all the previous valuations (most recent at the top). Whenever you are done adding cars, you could log out and register a new user to test history and adding cars with that.
Feel free to mess around with change/forgot password, and whatever else you want to investigate.</p>

<p>Coleman Stone: @ColemanStone</p>
<p>Drew Caldwell: @drewdcaldwell</p>
<p>Ben Earl: @benearl1</p>
<p>Caleb Vogl: @ctvogl</p>
