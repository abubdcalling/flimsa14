<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Contact Message</title>
</head>
<body>
    <h2>New Contact Message Received</h2>
    <p><strong>First Name:</strong> {{ $contact->first_name }}</p>
    <p><strong>Last Name:</strong> {{ $contact->last_name }}</p>
    <p><strong>Email:</strong> {{ $contact->email }}</p>
    <p><strong>Phone:</strong> {{ $contact->phone }}</p>
    <p><strong>Organization:</strong> {{ $contact->organization }}</p>
    <p><strong>City:</strong> {{ $contact->city }}</p>
    <p><strong>Help Request:</strong> {{ $contact->help }}</p>
</body>
</html>
