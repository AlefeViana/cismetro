<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.js" integrity="sha256-bd8XIKzrtyJ1O5Sh3Xp3GiuMIzWC42ZekvrMMD4GxRg=" crossorigin="anonymous"></script>
<script>
const fetch = axios.create({
    headers: {
      "Content-Type": "application/json",
      Authorization: "Bearer EAAVbBYO3ZBSABAK8I6A8rJQOp9SUDnVY7wm25ziUTkaSzn4JExRzFFuJkfRzwXwdVfZAeomu20cljI0Vv1waAA3XrvDNqGZBdSnIoSfhBJZC7I5T5DmcBmBI9r1uekdgP0s3tA3u3G1Ow8NU7dvhtwZC8QKiY71fJHwwCZCDRhjJMlEEaaIvK7V1JjVsuPP8ERkkuU1Qd7XQZDZD",
    },
  });
  const sendMenssage = async (cliente = null, celular, paciente, data, hora, prestador, profissional, procedimentos, local_atendimento = "", tel_local_atendimento) => {
  await fetch.post("https://graph.facebook.com/v15.0/111910305100983/messages", {
    messaging_product: "whatsapp",
    recipient_type: "individual",
    to: celular,
    type: "template",
    template: {
      name: "agendamento_v2_iconsorcio",
      language: {
        code: "pt_BR",
      },
      components: [
        {
          type: "header",
          parameters: [
            {
              type: "image",
              image: {
                link: "https://cisbaf.nuvemsitcon.com.br/cisbaf/guia_pac/_imagensMun/CISBAF_AGENDAMENTO.jpg",
              },
            },
          ],
        },
        {
          type: "body",
          parameters: [
            {
              type: "text",
              text: paciente,
            },
            {
              type: "text",
              text: data,
            },
            {
              type: "text",
              text: hora,
            },
            {
              type: "text",
              text: prestador,
            },
            {
              type: "text",
              text: profissional,
            },
            {
              type: "text",
              text: procedimentos,
            },
            {
              type: "text",
              text: local_atendimento,
            },
            {
              type: "text",
              text: tel_local_atendimento,
            },
          ],
        },
      ],
    },
  });

  sendMenssage(null, `+55$31973445021`, 'wemerson', '12/03/2023', '10:00', 'wemerson', 'wemerson', 'wemerson', 'wemerson', '973445021');
</script>
