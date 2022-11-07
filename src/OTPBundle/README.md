OTP
===
First of all, in order to get an OTP code you have to call the below URL:

/otp/generateCode/{moduleId}/{transactionId}/{userId}?additional_parameters 
OR call the method generateCodeAction in the OTP Controller

For example:
/otp/generateCode/5/2/300?param1="test"?param2="test1"
5 represents the moduleId
2 represents the transactionId
300 represents the userId
param1 and param2 are additional parameters that you can send and that will be returned to you in the end.

Then you will be redirected to a new route:

/otp/verifyCode/{geneatedEncodedId} 
OR call the verifyCodeAction in the OTP Controller

For example:
/otp/verifyCode/%25242y%252410%25245UYXIOYlTNp60TLorsroeOjT8uWyF2hpV1T7yiW%252FU6%252FwoTK%252FHEDSu

The code will be sent through email to the specified user and he will have to enter it in the form and submit

On submit:
If the code is correct and entered within 15 mins you will be redirected to a specified route by your request (each module will have it's own redirection route)

The following errors messages could occur:
"Code is invalid": If the password entered is not correct
"Code has already been used": If the same password is entered twice
"Code has expired": If the code is entered after more than 15 mins to when the code was sent



A Symfony project created on September 15, 2015, 2:52 pm