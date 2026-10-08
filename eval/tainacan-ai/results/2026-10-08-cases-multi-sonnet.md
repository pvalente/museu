applied preamble.txt (2519 chars)
Success: Applied inbcm-museologico.json
mode: multi (all views, prototype), model: site default, effort: default, max_tokens: site, museu_ai_image_max_edge: 0 (originals sent)
collection: Acervo Museológico (#127)

**Pass: 4/4** (Denominação and Classificação both right). Denominação 4/4, Classificação 4/4, Data de Produção 3/4 (informational).
Tokens: 41516 in + 9867 out over 4 requests (mean 10379 in, 2466 out).

| Case | Views | Denominação | Expected | ✓ | Classificação | ✓ | Data de Produção (got / expected) | Autor | Tokens in/out |
|---|---|---|---|---|---|---|---|---|---|
| m1 | 3 | Sextante | Sextante | ✓ exact | Equipamentos associados à topografia e à navegação | ✓ | — / 1922 ✗ | — | 10720/2369 |
| m2 | 2 | Moeda | Moeda | ✓ exact | Dinheiro | ✓ | 1913 / 1913 ✓ | — | 10821/2008 |
| m3 | 3 | Carimbo | Carimbo | ✓ exact | Equipamento de comunicação escrita | ✓ | — / — ✓ | J. XAVIER | 10720/2916 |
| m4 | 2 | Prato | Prato | ✓ exact | Objetos e equipamentos de serviço de alimentos | ✓ | — / — ✓ | Z.S.&Cº / BAVARIA[?] | 9255/2574 |

## m1: 3 views (claude-sonnet-5-5; in 10720, out 2369, thinking 1410, stop; system prompt 9117 chars; images 1600x1064 + 1600x1064 + 1064x1600; 26.6 s)
**Denominação** ✓ (expected "Sextante") — **Classificação** ✓ 

- inbcm-denominacao: "Sextante"
  evidence: "Tesauro (exact: \"Sextante\"): Equipamentos de atividades científicas e tecnológicas › Equipamentos associados à topografia e à navegação › Instrumento a reflexão › Sextante"
- titulo-2: "Sextante com estojo de madeira e placa gravada"
  evidence: "Imagem 1: instrumento de metal com arco graduado; Imagens 2 e 3: estojo de madeira com placa metálica gravada na tampa."
- inbcm-autor: null
- descricao-2: "Sextante de armação enegrecida, aparentemente de metal, com arco graduado em metal dourado, espelhos de vidro, lente e parafusos dourados. Um cabo de madeira torneada sustenta um mostrador circular branco com ponteiros. Acompanha estojo de madeira com alça e fecho de metal, calços de madeira com forro verde e placa metálica aparafusada na tampa. A armação apresenta escurecimento e manchas, e o interior do estojo tem perdas na madeira.\n\nInscrições: gravado em placa metálica, na tampa interna do estojo: \"Sextante \/ que depois de usado por \/ Santos=Dumont \/ foi por êle dado a Gago Coutinho \/ em 1922\"; gravado no arco graduado, parte inferior: \"[ilegível] Southampton[?]\"."
  evidence: "Imagem 1: instrumento, arco, espelhos, cabo de madeira torneada e mostrador; Imagem 3: estojo, calços e perdas na madeira; Imagem 2: placa com texto gravado; Imagem 1: gravação parcialmente legível no arco."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Metal","Madeira","Vidro","Gravação","Torneado"]
  evidence: ["Imagem 1: armação e arco de metal; Imagem 2: placa metálica","Imagens 1 e 3: cabo do instrumento e estojo de madeira","Imagens 1 e 3: espelhos e lente de vidro","Imagem 2: texto gravado na placa metálica","Imagem 1: cabo de madeira com perfil torneado"]
- inbcm-conservacao: "Regular"
  evidence: "Imagem 1: escurecimento e manchas no metal; Imagem 3: perdas e lascas na madeira do interior do estojo, sem comprometer a leitura do objeto."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Equipamentos associados à topografia e à navegação"
  evidence: "Tesauro: classe de Sextante"
  tesauro: names=["Sextante","Sextante com estojo de madeira e placa gravada"] -> ["Sextante (exact 1)"]

## m2: 2 views (claude-sonnet-5-5; in 10821, out 2008, thinking 1193, stop; system prompt 9117 chars; images 1600x1600 + 1600x1600; 21.8 s)
**Denominação** ✓ (expected "Moeda") — **Classificação** ✓ 

- inbcm-denominacao: "Moeda"
  evidence: "Tesauro (exact: \"Moeda\"): Objetos pecuniários › Dinheiro › Moeda"
- titulo-2: "Moeda de 2000 Réis de metal prateado"
  evidence: "Imagem 1: valor \"2000 \/ RÉIS\" em relevo ao centro; Imagens 1 e 2: metal de cor prateada."
- inbcm-autor: null
- descricao-2: "Moeda circular de metal prateado, com bordo de denteado fino. De um lado, há emblema com estrela cercada por raios, faixa e ramos, além do valor em relevo. Do outro, há perfil de cabeça voltada para a direita, cercado por estrelas. Apresenta desgaste, riscos, manchas escuras e leve irregularidade no bordo.\n\nInscrições: em relevo, Imagem 1, ao longo da borda: \"ORDEM E PROGRESSO\"; ao centro: \"2000 \/ RÉIS\"; na faixa do emblema: [ilegível] \/ \"1889\"[?]. Em relevo, Imagem 2, ao longo da borda: \"REPUBLICA DOS ESTADOS UNIDOS DO BRASIL\"; na parte inferior: \"1913\"."
  evidence: "Imagens 1 e 2: aspecto geral do objeto, relevos e inscrições cunhadas; desgaste e manchas visíveis, em especial na Imagem 2, à esquerda."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Metal"]
  evidence: ["Imagens 1 e 2: superfície metálica de cor prateada, com relevos."]
- inbcm-conservacao: "Regular"
  evidence: "Imagens 1 e 2: desgaste dos relevos, riscos, manchas escuras (Imagem 2, à esquerda) e leve irregularidade no bordo, sem perdas que comprometam a leitura."
- inbcm-localprod: null
- inbcm-dataprod: "1913"
  evidence: "Imagem 2: \"1913\" em relevo, na parte inferior."
- inbcm-classificacao: "Dinheiro"
  evidence: "Tesauro: classe de Moeda"
  tesauro: names=["Moeda","Moeda de 2000 Réis de metal prateado"] -> ["Moeda (exact 1)"]

## m3: 3 views (claude-sonnet-5-5; in 10720, out 2916, thinking 2040, stop; system prompt 9117 chars; images 1600x1064 + 1600x1064 + 1600x1064; 29.5 s)
**Denominação** ✓ (expected "Carimbo", or Carimbo (selo)) — **Classificação** ✓ 

- inbcm-denominacao: "Carimbo"
  evidence: "Tesauro (exact: \"Carimbo\"): Equipamento de comunicação › Equipamento de comunicação escrita › Carimbo. Alternativas: Carimbo (selo) — Equipamento de comunicação › Equipamento de comunicação escrita › Carimbo › Carimbo (selo); Carimbo (selo) — Equipamento de comunicação › Equipamento de comunicação escrita › Matriz de selo › Carimbo (selo)"
- titulo-2: "Carimbo com matriz circular inscrita CABANGU MANTIQUEIRA"
  evidence: "Imagem 2: texto em relevo na matriz circular, em espelho: CABANGU \/ S=D \/ MANTIQUEIRA."
- inbcm-autor: ["J. XAVIER"]
  evidence: ["Imagem 3: marca em baixo-relevo na lateral da base: \"J. XAVIER \/ RUA SACHET 18\"."]
- descricao-2: "Carimbo com cabo torneado de madeira aparentemente pintada de preto, de superfície brilhante, e base quadrangular de madeira em tom marrom-avermelhado. A face inferior da base tem matriz circular em relevo, de material não identificado, com letras em espelho. Há manchas escuras e trincas na matriz, pequenas perdas na tinta do cabo e desgaste nas bordas da base.\n\nInscrições: matriz circular, em relevo e em espelho (leitura invertida): \"CABANGU \/ S=D \/ MANTIQUEIRA\"; lateral da base, marcado em baixo-relevo: \"J. XAVIER \/ RUA SACHET 18\"; lateral da base, em tinta preta, na vertical: \"H-1790\"."
  evidence: "Imagem 1: cabo preto e base marrom-avermelhada; Imagem 2: matriz circular com trincas e manchas; Imagem 3: lateral da base com marcas e numeração."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Madeira","Torneado"]
  evidence: ["Imagens 1 e 3: cabo e base com aspecto de madeira, com veios visíveis na base.","Imagem 1: cabo de perfil arredondado, aparentemente torneado."]
- inbcm-conservacao: "Regular"
  evidence: "Imagem 2: trincas e manchas na matriz; Imagens 1 e 3: pequenas perdas de tinta no cabo, manchas e desgaste na base, sem comprometer a leitura do objeto."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Equipamento de comunicação escrita"
  evidence: "Tesauro: classe de Carimbo"
  tesauro: names=["Carimbo","Carimbo com matriz circular inscrita CABANGU MANTIQUEIRA"] -> ["Carimbo (exact 1)","Carimbo (selo) (exact 0.98)","Carimbo (selo) (exact 0.98)"]

## m4: 2 views (claude-sonnet-5-5; in 9255, out 2574, thinking 1762, stop; system prompt 9117 chars; images 1600x1288 + 1600x1200; 24.3 s)
**Denominação** ✓ (expected "Prato", or Prato decorativo) — **Classificação** ✓ 

- inbcm-denominacao: "Prato"
  evidence: "Tesauro (exact: \"Prato\"): Objetos domésticos › Objetos e equipamentos de serviço de alimentos › Prato. Alternativas: Prato (música) — Objetos de atividades artísticas › Objetos associados à música › Instrumento musical › Idiofone › Prato (música); Prato (música) — Objetos de atividades artísticas › Objetos associados à música › Instrumento musical › Membranofone › Bateria › Prato (música)"
- titulo-2: "Prato com decoração floral pintada e borda azul"
  evidence: "Imagem 1: guirlanda de rosas e margaridas pintadas e filete azul na borda."
- inbcm-autor: ["Z.S.&Cº \/ BAVARIA[?]"]
  evidence: ["Imagem 2: marca impressa em verde no centro da base, \"Z.S.&Cº \/ BAVAR...\"; final parcialmente encoberto."]
- descricao-2: "Prato circular de superfície branca e lisa, aparentemente de porcelana, com filete azul na borda. Uma guirlanda de flores pintadas à mão contorna o centro vazio, com quatro grandes rosas rosadas alternadas com margaridas em laranja, amarelo e azul e folhas verdes. Há pequenas perdas ou lascas na borda. A base tem anel de pé sem esmalte, de cor marrom clara.\n\nInscrições: carimbo impresso em verde, no centro da base: \"Z.S.&Cº \/ BAVARIA[?]\"; manuscrito em preto sobre faixa laranja, na base: \"72.1.40.\""
  evidence: "Imagem 1: decoração floral e borda azul; pequenas lascas na borda. Imagem 2: marca verde e numeração manuscrita sobre faixa laranja na base."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Porcelana","Pintura"]
  evidence: ["Imagens 1 e 2: corpo branco e vitrificado, aparentemente porcelana, com anel de pé sem esmalte.","Imagem 1: flores e filete azul com pinceladas visíveis, aparentemente pintados à mão."]
- inbcm-conservacao: "Regular"
  evidence: "Imagem 1: pequenas lascas ou perdas na borda, no canto inferior esquerdo e no alto à direita; a decoração segue legível."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Objetos e equipamentos de serviço de alimentos"
  evidence: "Tesauro: classe de Prato"
  tesauro: names=["Prato","Prato com decoração floral pintada e borda azul"] -> ["Prato (exact 1)","Prato (música) (exact 0.98)","Prato (música) (exact 0.98)"]

