# codeigniter-qa

A questions and answers website created using CodeIgniter as part of a university assignment.
<br/>
<b>Note:</b> Some of the functionality had to be implemented in a specific way based on the assignment specification.
If this was not based on an assignment then the implementation would differ.
<br/>
<br/>
The project has not been maintained since the submission of the assignment.
<br/>
<br/>
<b>Overview of functionality:</b>
<br/>
Allows users to post questions and provide answers to existing questions.<br/>
Questions can be given a title, description, category and any number of tags.<br/>
Users may search for questions based on a category or matching terms.<br/>
An AJAX based dynamic search is available which searches through the titles of the questions.<br/>
An ordinary search is also available that will search through the whole question.<br/>
Users can click on any of the given categories and all questions in that category will be displayed.<br/>
All answers to questions have a rating that is determined by the vote (+1/-1) that other users can give.<br/>
Users can vote only once on any given answer.<br/>
Users cannot vote on their own answers.<br/>
The answers to a given question are displayed in order based on their rating.<br/>
Voting on an answer will update the page dynamically using AJAX.<br/>
All answers to that question will be reloaded and sorted to take into account the given rating.<br/>
Posting an answer to a question, just like when a user votes, will force the page to dynamically update.<br/>
Users have basic profiles which allow them to view their rating and update their details such as email and password.