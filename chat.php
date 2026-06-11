<?php

session_start();

global $conn;
include "connection.php";

$conversationId = 1;

$query = mysqli_query($conn, "SELECT * FROM messages WHERE conversation_id = '$conversationId'");
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>App Chat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        const userId = "<?= $_SESSION['user_id'] ?>"

        Pusher.logToConsole = true;

        const pusher = new Pusher('cd37a9cac61b05b6944b', {
            cluster: 'ap1'
        });

        const channel = pusher.subscribe('chat');

        channel.bind('receive', function(data) {
            if (data.sender_id === userId) {
                return
            }

            const chatContent = data.content;

            const chatDiv = document.createElement('div');
            chatDiv.className = 'flex';

            const innerDiv = document.createElement('div');
            innerDiv.className = 'bg-gray-300 text-black p-2 rounded-lg max-w-xs';
            innerDiv.textContent = chatContent;

            chatDiv.appendChild(innerDiv);
            wrapperChat.appendChild(chatDiv);
        });
    </script>

</head>

<body>
    <!-- component -->
    <div class="bg-gray-100 h-screen flex flex-col max-w-lg mx-auto">
        <div class="bg-blue-500 p-4 text-white flex justify-between items-center">
            <a href="accounts.php" id="login" class="hover:bg-blue-400 rounded-md p-1">
                <svg width="25px" height="25px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <circle cx="12" cy="6" r="4" stroke="#ffffff" stroke-width="1.5"></circle>
                        <path d="M15 20.6151C14.0907 20.8619 13.0736 21 12 21C8.13401 21 5 19.2091 5 17C5 14.7909 8.13401 13 12 13C15.866 13 19 14.7909 19 17C19 17.3453 18.9234 17.6804 18.7795 18" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round"></path>
                    </g>
                </svg>
            </a>

            <a href="logout.php" id="logout" class="hover:bg-blue-400 rounded-md p-1" onclick="return confirm('Apakah Anda Ingin Logout ?')">
                <svg width="25px" height="25px" class="w-[24px] h-[24px] fill-[#ffffff]" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <circle cx="12" cy="6" r="4" stroke="#ffffff" stroke-width="1.5"></circle>
                        <path d="M502.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L402.7 224 192 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l210.7 0-73.4 73.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l128-128zM160 96c17.7 0 32-14.3 32-32s-14.3-32-32-32L96 32C43 32 0 75 0 128L0 384c0 53 43 96 96 96l64 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-64 0c-17.7 0-32-14.3-32-32l0-256c0-17.7 14.3-32 32-32l64 0z"></path>
                    </g>
                </svg>
            </a>
        </div>

        <div class="flex-1 overflow-y-auto p-4">
            <div class="wrapper-chat flex flex-col space-y-2">
                <?php while ($chat = mysqli_fetch_assoc($query)): ?>

                    <?php if ($chat['sender_id'] == $_SESSION['user_id']): ?>
                        <div class="flex justify-end">
                            <div class="bg-blue-200 text-black p-2 rounded-lg max-w-xs">
                                <?= $chat['content'] ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($chat['sender_id'] != $_SESSION['user_id']): ?>
                        <div class="flex">
                            <div class="bg-gray-300 text-black p-2 rounded-lg max-w-xs">
                                <?= $chat['content'] ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endwhile; ?>
            </div>
        </div>

        <form id="form-chat" action="" method="POST">
            <div class="bg-white p-4 flex items-center">
                <input type="text" name="content" placeholder="Type your message..." class="flex-1 border rounded-full px-4 py-2 focus:outline-none" id="content">
                <button type="submit" class="bg-blue-500 text-white rounded-full p-2 ml-2 hover:bg-blue-600 focus:outline-none">
                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ffffff">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path d="M11.5003 12H5.41872M5.24634 12.7972L4.24158 15.7986C3.69128 17.4424 3.41613 18.2643 3.61359 18.7704C3.78506 19.21 4.15335 19.5432 4.6078 19.6701C5.13111 19.8161 5.92151 19.4604 7.50231 18.7491L17.6367 14.1886C19.1797 13.4942 19.9512 13.1471 20.1896 12.6648C20.3968 12.2458 20.3968 11.7541 20.1896 11.3351C19.9512 10.8529 19.1797 10.5057 17.6367 9.81135L7.48483 5.24303C5.90879 4.53382 5.12078 4.17921 4.59799 4.32468C4.14397 4.45101 3.77572 4.78336 3.60365 5.22209C3.40551 5.72728 3.67772 6.54741 4.22215 8.18767L5.24829 11.2793C5.34179 11.561 5.38855 11.7019 5.407 11.8459C5.42338 11.9738 5.42321 12.1032 5.40651 12.231C5.38768 12.375 5.34057 12.5157 5.24634 12.7972Z" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </g>
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <script>
        const form = document.querySelector('#form-chat')
        const wrapperChat = document.querySelector('.wrapper-chat')

        form.addEventListener('submit', (event) => {
            event.preventDefault()

            const chatContent = document.querySelector('#content').value;

            const chatDiv = document.createElement('div');
            chatDiv.className = 'flex justify-end';

            const innerDiv = document.createElement('div');
            innerDiv.className = 'bg-blue-200 text-black p-2 rounded-lg max-w-xs';
            innerDiv.textContent = chatContent;

            chatDiv.appendChild(innerDiv);
            wrapperChat.appendChild(chatDiv);

            fetch('saveChat.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        content: chatContent,
                        conversationId: '<?= $conversationId ?>'
                    })
                })
                .then(response => response.json())
                .then(data => console.log(data))
                .catch(error => console.error('Error:', error));

            document.querySelector('#content').value = ''
        })
    </script>
</body>

</html>