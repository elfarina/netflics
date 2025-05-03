
$(document).ready(function () {
    // Funzione per caricare la lista dei contenuti
    function loadContent() {
        $.ajax({
            url: 'get-content.php', // URL del file PHP che restituirà i contenuti
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    let contentTable = $('.content-table');
                    contentTable.empty(); // Pulisce la tabella esistente

                    // Creiamo la tabella per visualizzare i contenuti
                    let tableHTML = '<table class="table table-striped">';
                    tableHTML += '<thead><tr><th>Titolo</th><th>Descrizione</th><th>Anno</th><th>Voto</th><th>Azioni</th></tr></thead>';
                    tableHTML += '<tbody>';

                    // Loop attraverso i contenuti e crea le righe della tabella
                    $.each(response.data, function (index, content) {
                        tableHTML += '<tr>';
                        tableHTML += `<td>${content.title}</td>`;
                        tableHTML += `<td>${content.description}</td>`;
                        tableHTML += `<td>${content.release_year}</td>`;
                        tableHTML += `<td>${content.rating}</td>`;
                        tableHTML += `<td><button class="btn btn-danger delete-content" data-id="${content.id}">Elimina</button></td>`;
                        tableHTML += '</tr>';
                    });

                    tableHTML += '</tbody></table>';
                    contentTable.html(tableHTML); // Inserisce la tabella nel DOM

                    // Aggiungi un gestore di eventi per il pulsante di eliminazione
                    $('.delete-content').on('click', function () {
                        let contentId = $(this).data('id');
                        deleteContent(contentId);
                    });
                } else {
                    $('#errorMessages').show().text(response.message);
                }
            },
            error: function () {
                $('#errorMessages').show().text('Si è verificato un errore durante il recupero dei contenuti.');
            }
        });
    }

    // Funzione per eliminare un contenuto
    function deleteContent(contentId) {
        if (confirm('Sei sicuro di voler eliminare questo contenuto?')) {
            $.ajax({
                url: 'delete-content.php', // URL del file PHP che gestisce l'eliminazione
                method: 'POST',
                data: { id: contentId },
                success: function (response) {
                    if (response.status === 'success') {
                        loadContent(); // Ricarica i contenuti dopo l'eliminazione
                    } else {
                        $('#errorMessages').show().text(response.message);
                    }
                },
                error: function () {
                    $('#errorMessages').show().text('Si è verificato un errore durante l\'eliminazione del contenuto.');
                }
            });
        }
    }

    // Carica i contenuti quando la pagina è pronta
    loadContent();
});
