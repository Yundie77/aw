document.addEventListener('DOMContentLoaded', function () {
    const contenedorPrincipalChatDropdown = document.querySelector('.chat-dropdown-container');
    if (!contenedorPrincipalChatDropdown) {
        return;
    }

    const contenedorListaGrupos = contenedorPrincipalChatDropdown.querySelector('.chat-group-list');
    const contenedorInterfazChat = contenedorPrincipalChatDropdown.querySelector('.chat-interface-container');
    const nombreGrupoChatDisplay = contenedorInterfazChat.querySelector('.chat-group-name-display');
    const areaMensajes = contenedorInterfazChat.querySelector('.chat-messages-area');
    const entradaMensaje = contenedorInterfazChat.querySelector('.chat-message-input');
    const botonEnviar = contenedorInterfazChat.querySelector('.chat-send-btn');
    const botonVolverAGrupos = contenedorInterfazChat.querySelector('.chat-back-to-groups');
    const botonCerrarChat = contenedorInterfazChat.querySelector('.chat-close-btn');

    let idGrupoActivo = null;
    let idIntervaloRefresco = null;

    function escaparHTML(texto) {
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(texto || ''));
        return div.innerHTML;
    }

    async function cargarMensajes(idGrupo) {
        if (!idGrupo) return;
        idGrupoActivo = idGrupo;
        try {
            const respuesta = await fetch(`chat_mensajes.php?group_id=${idGrupo}`);
            if (!respuesta.ok) {
                throw new Error(`Error HTTP: ${respuesta.status}`);
            }
            const datos = await respuesta.json();

            if (datos.exito) {
                areaMensajes.innerHTML = '';
                if (datos.mensajes && datos.mensajes.length > 0) {
                    datos.mensajes.forEach(msg => {
                        const divMensaje = document.createElement('div');
                        divMensaje.classList.add('chat-message');
                        if (msg.usuario_id === datos.id_usuario_actual) {
                            divMensaje.classList.add('sent');
                        } else {
                            divMensaje.classList.add('received');
                        }
                        const nombreRemitente = escaparHTML(msg.nombre_usuario || 'Usuario');
                        const contenidoMsg = escaparHTML(msg.contenido);
                        let fechaMsg = 'Hora desconocida';
                        if (msg.fecha) {
                            try {
                                fechaMsg = new Date(msg.fecha.replace(' ', 'T')+'Z').toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', timeZone: 'Europe/Madrid' });
                            } catch (e) { console.warn("Error al parsear fecha: ", msg.fecha)}
                        }

                        divMensaje.innerHTML = `<strong>${nombreRemitente}:</strong> ${contenidoMsg} <span class="chat-message-time">${fechaMsg}</span>`;
                        areaMensajes.appendChild(divMensaje);
                    });
                } else {
                    areaMensajes.innerHTML = '<p class="chat-info">No hay mensajes en este grupo.</p>';
                }
                areaMensajes.scrollTop = areaMensajes.scrollHeight;
            } else {
                console.error('Error al cargar mensajes:', datos.error);
                areaMensajes.innerHTML = `<p class="chat-error">Error: ${escaparHTML(datos.error)}</p>`;
            }
        } catch (error) {
            console.error('Error de fetch al cargar mensajes:', error);
            areaMensajes.innerHTML = '<p class="chat-error">Error de conexión al cargar mensajes.</p>';
        }
    }

    async function enviarMensaje() {
        if (!idGrupoActivo || entradaMensaje.value.trim() === '') return;

        const contenido = entradaMensaje.value.trim();
        const formData = new FormData();
        formData.append('group_id', idGrupoActivo);
        formData.append('contenido', contenido);

        try {
            const respuesta = await fetch('chat_enviar.php', {
                method: 'POST',
                body: formData
            });
            if (!respuesta.ok) {
                throw new Error(`Error HTTP: ${respuesta.status}`);
            }
            const datos = await respuesta.json();

            if (datos.exito) {
                entradaMensaje.value = '';
                cargarMensajes(idGrupoActivo);
            } else {
                console.error('Error al enviar mensaje:', datos.error);
                alert(`Error al enviar: ${escaparHTML(datos.error)}`);
            }
        } catch (error) {
            console.error('Error de fetch al enviar mensaje:', error);
            alert('Error de conexión al enviar mensaje.');
        }
    }

    contenedorPrincipalChatDropdown.querySelectorAll('.chat-group-item').forEach(item => {
        item.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const idGrupo = this.dataset.groupId;
            const nombreGrupo = this.dataset.groupName;

            nombreGrupoChatDisplay.textContent = escaparHTML(nombreGrupo);
            contenedorListaGrupos.style.display = 'none';
            contenedorInterfazChat.style.display = 'flex';
            contenedorInterfazChat.setAttribute('data-active-group-id', idGrupo);
            
            cargarMensajes(idGrupo);

            if (idIntervaloRefresco) clearInterval(idIntervaloRefresco);
            idIntervaloRefresco = setInterval(() => cargarMensajes(idGrupoActivo), 15000);
        });
    });

    botonEnviar.addEventListener('click', function(e) {
        e.stopPropagation();
        enviarMensaje();
    });

    entradaMensaje.addEventListener('keypress', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            e.stopPropagation();
            enviarMensaje();
        }
    });

    botonVolverAGrupos.addEventListener('click', function(e) {
        e.stopPropagation();
        if (idIntervaloRefresco) clearInterval(idIntervaloRefresco);
        idIntervaloRefresco = null;
        idGrupoActivo = null;
        contenedorInterfazChat.style.display = 'none';
        contenedorListaGrupos.style.display = 'block';
        nombreGrupoChatDisplay.textContent = '';
        areaMensajes.innerHTML = '';
        contenedorInterfazChat.removeAttribute('data-active-group-id');
    });
    
    botonCerrarChat.addEventListener('click', function(e) {
        e.stopPropagation();
        botonVolverAGrupos.click(); 
    });

    contenedorInterfazChat.addEventListener('click', function(e) {
        e.stopPropagation();
    });
});