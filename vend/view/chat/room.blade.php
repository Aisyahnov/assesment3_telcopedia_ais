<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chat</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: #fafafa;
        }

        /* Container utama 2 kolom */
        .chat-wrapper {
            display: flex;
            height: 85vh;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        /* LEFT SIDEBAR (Chat List) */
        .chat-list {
            width: 28%;
            border-right: 1px solid #eee;
            overflow-y: auto;
            padding: 15px;
        }
        .chat-list h5 {
            font-weight: 600;
            margin-bottom: 15px;
        }

        .chat-user {
            padding: 10px 12px;
            border-radius: 10px;
            cursor: pointer;
        }
        .chat-user.active {
            background: #f1f1f1;
        }
        .chat-user:hover {
            background: #f7f7f7;
        }

        /* RIGHT (ROOM) */
        .chat-room {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 15px;
        }

        .messages-box {
            flex: 1;
            overflow-y: auto;
            padding: 10px 20px;
        }

        /* Chat bubble */
        .bubble {
            padding: 10px 14px;
            border-radius: 12px;
            max-width: 55%;
            margin-bottom: 8px;
            display: inline-block;
        }

        .bubble.me {
            background: #ffeded;
            color: #c70000;
            margin-left: auto;
        }
        .bubble.them {
            background: #ececec;
        }

        .send-box {
            padding: 15px;
            border-top: 1px solid #eee;
        }

        .send-input {
            border: 1px solid #ddd;
            border-radius: 30px;
            padding: 12px 20px;
        }
    </style>
</head>

<body class="p-4">

<div class="container">

    <div class="chat-wrapper">

        <!-- LEFT SIDEBAR : CHAT LIST -->
        <div class="chat-list">
            <h5>Chat</h5>

            @foreach(\App\Models\Chat::where('buyer_id', auth()->id())
                ->orWhere('seller_id', auth()->id())
                ->with('product','seller','buyer')
                ->get() as $c)

                <a href="{{ route('chat.room', $c->id) }}" style="text-decoration:none; color: inherit;">
                    <div class="chat-user {{ $c->id == $chat->id ? 'active' : '' }}">
                        <strong>
                            {{ $c->seller->id == auth()->id() ? $c->buyer->name : $c->seller->name }}
                        </strong>
                        <div class="text-muted small">
                            {{ $c->messages->last()->message ?? 'No messages yet' }}
                        </div>
                        <div class="text-muted small">
                            {{ optional($c->messages->last())->created_at?->format('Y-m-d H:i') ?? '' }}
                        </div>
                    </div>
                </a>

            @endforeach

        </div>

        <!-- RIGHT CHAT ROOM -->
        <div class="chat-room">

            <div class="messages-box">
                  @foreach($chat->messages as $msg)

                      <div class="mb-3">
                          <strong>{{ $msg->sender->name ?? 'Unknown User' }}</strong><br>

                          <span class="bg-white p-2 rounded shadow-sm d-inline-block">
                              {{ $msg->message }}
                          </span>

                          <div>
                              <small class="text-muted">
                                  {{ $msg->created_at ? $msg->created_at->diffForHumans() : '-' }}
                              </small>
                          </div>
                      </div>

                      @if($msg->sender_id == auth()->id())
                          <div class="text-end mt-1">
                              <a href="#" onclick="editMsg({{ $msg->id }}, '{{ $msg->message }}')" class="text-primary small">Edit</a> |
                              <form action="{{ route('chat.message.delete', [$chat->id, $msg->id]) }}" 
                                    method="POST" class="d-inline">
                                  @csrf
                                  @method('DELETE')
                                  <button class="btn btn-link text-danger small p-0 m-0">Delete</button>
                              </form>
                          </div>
                      @endif

                  @endforeach
            </div>

            <!-- INPUT MESSAGE -->
            <form action="{{ route('chat.send', $chat->id) }}" method="POST" class="send-box">
                @csrf
                <div class="d-flex">
                    <input type="text" name="message" placeholder="Type your message here..." class="form-control send-input" required>
                    <button class="btn btn-link ms-2">
                        <i class="fa fa-paper-plane fa-lg"></i>
                    </button>
                </div>
            </form>

        </div>

    </div>
</div>

<div class="modal" id="editModal">
  <div class="modal-dialog">
    <div class="modal-content p-3">
      <form method="POST" id="editForm">
          @csrf
          @method('PUT')
          <div class="mb-3">
              <label>Edit Message</label>
              <input type="text" name="message" id="editInput" class="form-control">
          </div>
          <button class="btn btn-danger">Save</button>
      </form>
    </div>
  </div>
</div>

<script>
function editMsg(id, text) {
    document.getElementById('editInput').value = text;

    document.getElementById('editForm').action =
        "{{ url('/chat/' . $chat->id . '/message') }}/" + id + "/update";

    const modal = new bootstrap.Modal(document.getElementById('editModal'));
    modal.show();
}
</script>

</body>
</html>
