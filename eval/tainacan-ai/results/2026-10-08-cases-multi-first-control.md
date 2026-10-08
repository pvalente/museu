applied preamble.txt (2519 chars)
Success: Applied inbcm-museologico.json
mode: multi (first view only, prototype), model: site default, effort: default, max_tokens: site, museu_ai_image_max_edge: 0 (originals sent)
collection: Acervo Museológico (#127)

**Pass: 4/4** (Denominação and Classificação both right). Denominação 4/4, Classificação 4/4, Data de Produção 2/4 (informational).
Tokens: 26476 in + 4436 out over 4 requests (mean 6619 in, 1109 out).

| Case | Views | Denominação | Expected | ✓ | Classificação | ✓ | Data de Produção (got / expected) | Autor | Tokens in/out |
|---|---|---|---|---|---|---|---|---|---|
| m1 | 1 | Sextante | Sextante | ✓ exact | Equipamentos associados à topografia e à navegação | ✓ | — / 1922 ✗ | — | 6213/1573 |
| m2 | 1 | Moeda | Moeda | ✓ exact | Dinheiro | ✓ | — / 1913 ✗ | — | 7373/1530 |
| m3 | 1 | Carimbo | Carimbo | ✓ exact | Equipamento de comunicação escrita | ✓ | — / — ✓ | — | 6213/653 |
| m4 | 1 | Prato | Prato | ✓ exact | Objetos e equipamentos de serviço de alimentos | ✓ | — / — ✓ | — | 6677/680 |

## m1: 1 view (claude-sonnet-5-5; in 6213, out 1573, thinking 867, stop; system prompt 9117 chars; images 1600x1064; 15.4 s)
**Denominação** ✓ (expected "Sextante") — **Classificação** ✓ 

- inbcm-denominacao: "Sextante"
  evidence: "Tesauro (exact: \"Sextante\"): Equipamentos de atividades científicas e tecnológicas › Equipamentos associados à topografia e à navegação › Instrumento a reflexão › Sextante"
- titulo-2: "Sextante de metal com arco graduado, lupa e cabo de madeira"
  evidence: "Armação metálica escurecida, arco graduado amarelado, lupa articulada e cabo de madeira torneada com mostrador"
- inbcm-autor: null
- descricao-2: "Sextante com armação de metal escurecido e arco graduado em metal amarelado. Parafusos e outros detalhes são aparentemente de latão. Tem espelhos de vidro, luneta com suporte, lupa articulada e cabo de madeira torneada avermelhada com pequeno mostrador circular de ponteiros. A superfície da armação apresenta manchas e oxidação.\n\nInscrições: gravado, na borda inferior do arco graduado, à direita: \"[ilegível] Southampton[?]\""
  evidence: "Aspecto geral do objeto; inscrição gravada em letra cursiva na borda do arco, à direita do centro, parcialmente legível"
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Metal","Madeira","Vidro","Torneado","Gravação"]
  evidence: ["Armação e arco em metal escurecido e amarelado, parafusos aparentemente de latão","Cabo central em madeira avermelhada","Espelhos e lente da lupa em vidro","Cabo de madeira com perfil torneado","Inscrição gravada no arco graduado"]
- inbcm-conservacao: "Regular"
  evidence: "Manchas e oxidação visíveis na armação escurecida, sem perdas ou fraturas evidentes"
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Equipamentos associados à topografia e à navegação"
  evidence: "Tesauro: classe de Sextante"
  tesauro: names=["Sextante","Sextante de metal com arco graduado, lupa e cabo de madeira"] -> ["Sextante (exact 1)"]

## m2: 1 view (claude-sonnet-5-5; in 7373, out 1530, thinking 792, stop; system prompt 9117 chars; images 1600x1600; 14.8 s)
**Denominação** ✓ (expected "Moeda") — **Classificação** ✓ 

- inbcm-denominacao: "Moeda"
  evidence: "Tesauro (exact: \"Moeda\"): Objetos pecuniários › Dinheiro › Moeda"
- titulo-2: "Moeda de 2000 réis com brasão e lema Ordem e Progresso"
  evidence: "Texto em relevo \"2000 RÉIS\" ao centro e \"ORDEM E PROGRESSO\" ao longo da borda."
- inbcm-autor: null
- descricao-2: "Moeda circular de metal prateado, com relevo cunhado e borda com serrilha de pequenos pontos. Ao centro superior há um brasão com estrela e raios, sobre uma faixa com texto, e ao centro inferior o valor, ladeado por ramos. Apresenta desgaste generalizado, riscos e pequenas irregularidades na borda.\n\nInscrições: em relevo, ao longo da borda: \"ORDEM E PROGRESSO\"; em relevo, ao centro inferior: \"2000 \/ RÉIS\"; em relevo, na faixa sob o brasão, parcialmente legível: \"[ilegível]DENOVE[?] [ilegível] 1889[?]\"."
  evidence: "Moeda inteira em um único lado visível; letras em relevo na borda, valor ao centro e faixa sob o brasão; desgaste e irregularidades na borda superior esquerda e direita."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Metal"]
  evidence: ["Superfície metálica prateada com relevo cunhado."]
- inbcm-conservacao: "Regular"
  evidence: "Desgaste visível, riscos e pequenas irregularidades na borda, sem perda que comprometa a leitura do objeto."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Dinheiro"
  evidence: "Tesauro: classe de Moeda"
  tesauro: names=["Moeda","Moeda de 2000 réis com brasão e lema Ordem e Progresso"] -> ["Moeda (exact 1)"]

## m3: 1 view (claude-sonnet-5-5; in 6213, out 653, thinking 0, stop; system prompt 9117 chars; images 1600x1064; 15.6 s)
**Denominação** ✓ (expected "Carimbo", or Carimbo (selo)) — **Classificação** ✓ 

- inbcm-denominacao: "Carimbo"
  evidence: "Tesauro (exact: \"Carimbo\"): Equipamento de comunicação › Equipamento de comunicação escrita › Carimbo. Alternativas: Carimbo (selo) — Equipamento de comunicação › Equipamento de comunicação escrita › Carimbo › Carimbo (selo); Carimbo (selo) — Equipamento de comunicação › Equipamento de comunicação escrita › Matriz de selo › Carimbo (selo)"
- titulo-2: "Carimbo com cabo preto e base de madeira avermelhada"
  evidence: "Cabo preto torneado sobre base retangular de madeira em tom avermelhado."
- inbcm-autor: null
- descricao-2: "Carimbo manual com cabo preto de perfil torneado, alargado no topo e na base, fixado sobre base retangular de madeira aparentemente envernizada em tom marrom-avermelhado. A lateral direita da base é de madeira clara, com marcas ou texto muito desgastados. O cabo apresenta desgaste da tinta no topo e pequena lasca junto à base. A superfície de carimbagem não está visível.\n\nInscrições: gravado ou carimbado, na lateral clara da base: [ilegível]"
  evidence: "Cabo preto com brilho e desgaste no topo; base retangular marrom-avermelhada; lateral clara com marcas ilegíveis na região inferior direita."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Madeira","Torneado"]
  evidence: ["Cabo e base com aspecto de madeira, com veios visíveis na base e madeira clara aparente na lateral.","Cabo com perfil arredondado e simétrico, aparentemente torneado."]
- inbcm-conservacao: "Regular"
  evidence: "Desgaste da pintura no topo do cabo, pequena lasca junto à base do cabo, arranhões e marcas na base de madeira."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Equipamento de comunicação escrita"
  evidence: "Tesauro: classe de Carimbo"
  tesauro: names=["Carimbo","Carimbo com cabo preto e base de madeira avermelhada"] -> ["Carimbo (exact 1)","Carimbo (selo) (exact 0.98)","Carimbo (selo) (exact 0.98)"]

## m4: 1 view (claude-sonnet-5-5; in 6677, out 680, thinking 0, stop; system prompt 9117 chars; images 1600x1288; 8.5 s)
**Denominação** ✓ (expected "Prato", or Prato decorativo) — **Classificação** ✓ 

- inbcm-denominacao: "Prato"
  evidence: "Tesauro (exact: \"Prato\"): Objetos domésticos › Objetos e equipamentos de serviço de alimentos › Prato. Alternativas: Prato (música) — Objetos de atividades artísticas › Objetos associados à música › Instrumento musical › Idiofone › Prato (música); Prato (música) — Objetos de atividades artísticas › Objetos associados à música › Instrumento musical › Membranofone › Bateria › Prato (música)"
- titulo-2: "Prato com decoração floral pintada e filete azul"
  evidence: "Pintura de rosas rosa-magenta e flores azuis, laranja e amarelas em guirlanda, com filete azul na borda."
- inbcm-autor: null
- descricao-2: "Prato raso circular, de superfície lisa e brilhante, aparentemente de porcelana branca. A decoração é pintada à mão, em guirlanda que acompanha a borda, com quatro rosas rosa-magenta alternadas com flores azuis, laranja e amarelas e folhas verdes. O centro permanece branco, e a borda é contornada por um filete azul. Há pequenos pontos de desgaste ou perda no filete da borda, nas partes superior direita e inferior esquerda."
  evidence: "Vista frontal do prato: esmalte branco brilhante, pinceladas visíveis nas flores, filete azul no contorno, pequenas falhas marrons na borda inferior esquerda e superior direita."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Porcelana","Pintura"]
  evidence: ["Corpo branco, liso e brilhante, aparentemente de porcelana.","Flores e folhas com pinceladas visíveis, indicando decoração pintada à mão."]
- inbcm-conservacao: "Regular"
  evidence: "Pequenas perdas ou desgastes visíveis no filete azul da borda (superior direita e inferior esquerda), sem comprometer a leitura do objeto."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Objetos e equipamentos de serviço de alimentos"
  evidence: "Tesauro: classe de Prato"
  tesauro: names=["Prato","Prato com decoração floral pintada e filete azul"] -> ["Prato (exact 1)","Prato (música) (exact 0.98)","Prato (música) (exact 0.98)"]

