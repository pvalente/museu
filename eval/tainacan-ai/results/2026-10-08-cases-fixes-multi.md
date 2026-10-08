applied preamble.txt (2665 chars)
Success: Applied inbcm-museologico.json
mode: multi (all views, prototype), model: site default, effort: default, max_tokens: site, museu_ai_image_max_edge: 1568
collection: Acervo Museológico (#127)

**Pass: 4/4** (Denominação and Classificação both right). Denominação 4/4, Classificação 4/4, Data de Produção 4/4 (informational).
Tokens: 40918 in + 9458 out over 4 requests (mean 10229 in, 2364 out).

| Case | Views | Denominação | Expected | ✓ | Classificação | ✓ | Data de Produção (got / expected) | Autor | Tokens in/out |
|---|---|---|---|---|---|---|---|---|---|
| m1 | 3 | Sextante | Sextante | ✓ exact | Equipamentos associados à topografia e à navegação | ✓ | — / — ✓ | — | 10629/2338 |
| m2 | 2 | Moeda | Moeda | ✓ exact | Dinheiro | ✓ | 1913 / 1913 ✓ | — | 10502/1754 |
| m3 | 3 | Carimbo | Carimbo | ✓ exact | Equipamento de comunicação escrita | ✓ | — / — ✓ | J. XAVIER | 10629/2874 |
| m4 | 2 | Prato | Prato | ✓ exact | Objetos e equipamentos de serviço de alimentos | ✓ | — / — ✓ | Z.S.&C° | 9158/2492 |

## m1: 3 views (claude-sonnet-5-5; in 10629, out 2338, thinking 1456, stop; system prompt 0 chars; images 1568x1043 + 1568x1043 + 1043x1568; 29.5 s)
**Denominação** ✓ (expected "Sextante") — **Classificação** ✓ 

- inbcm-denominacao: "Sextante"
  evidence: "Tesauro (exact: \"Sextante\"): Equipamentos de atividades científicas e tecnológicas › Equipamentos associados à topografia e à navegação › Instrumento a reflexão › Sextante"
- titulo-2: "Sextante em estojo de madeira com placa gravada"
  evidence: "Imagem 2 e Imagem 3: placa metálica gravada na tampa do estojo com a palavra \"Sextante\"; Imagem 3: estojo de madeira."
- inbcm-autor: null
- descricao-2: "Sextante de metal enegrecido com partes de latão aparente, arco graduado, espelhos de vidro, lente de aumento e mostrador circular em suporte de madeira. Acompanha estojo de madeira com alça e fecho metálicos e divisões internas com apoios forrados de feltro verde. O instrumento apresenta desgaste e oxidação, e o estojo tem lascas e perdas na madeira.\n\nInscrições: gravado em placa metálica fixada com parafusos no interior da tampa do estojo: \"Sextante \/ que depois de usado por \/ Santos=Dumont \/ foi por êle dado a Gago Coutinho \/ em 1922\"."
  evidence: "Imagem 1: instrumento de metal escuro e latão, arco graduado, espelho, lente e mostrador. Imagem 3: estojo de madeira com alça, feltro verde e lascas internas. Imagem 2: placa com texto gravado em letra cursiva."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Metal","Madeira","Vidro","Gravação"]
  evidence: ["Imagem 1: estrutura enegrecida e peças em latão; Imagem 3: alça e fecho metálicos","Imagem 3: estojo de madeira; Imagem 1: suporte de madeira torneada com mostrador","Imagem 1 e Imagem 3: espelhos e lente de aumento de vidro","Imagem 2: texto gravado na placa metálica"]
- inbcm-conservacao: "Regular"
  evidence: "Imagem 1: desgaste e oxidação nas superfícies metálicas; Imagem 3: lascas e perdas na madeira interna do estojo, sem comprometer a leitura do objeto."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Equipamentos associados à topografia e à navegação"
  evidence: "Tesauro: classe de Sextante"
  tesauro: names=["Sextante","Sextante em estojo de madeira com placa gravada"] -> ["Sextante (exact 1)"]

## m2: 2 views (claude-sonnet-5-5; in 10502, out 1754, thinking 928, stop; system prompt 0 chars; images 1568x1568 + 1568x1568; 20.6 s)
**Denominação** ✓ (expected "Moeda") — **Classificação** ✓ 

- inbcm-denominacao: "Moeda"
  evidence: "Tesauro (exact: \"Moeda\"): Objetos pecuniários › Dinheiro › Moeda"
- titulo-2: "Moeda de 2000 réis com inscrição Ordem e Progresso"
  evidence: "Imagem 1: \"2000 RÉIS\" ao centro inferior e \"ORDEM E PROGRESSO\" em arco na borda."
- inbcm-autor: null
- descricao-2: "Moeda circular de metal prateado, aparentemente cunhada, com borda serrilhada. Uma face traz brasão com estrela central, faixa, ramos e o valor em relevo. A outra face traz busto feminino de perfil, cercado por estrelas. Apresenta desgaste, riscos e manchas escuras.\n\nInscrições: em relevo, em uma face, em arco: \"ORDEM E PROGRESSO\"; abaixo, ao centro: \"2000 \/ RÉIS\"; na faixa sob o brasão: [ilegível]. Na outra face, em arco: \"REPUBLICA DOS ESTADOS UNIDOS DO BRASIL\"; na parte inferior: \"1913\"."
  evidence: "Imagem 1: brasão, ramos, \"ORDEM E PROGRESSO\", \"2000 RÉIS\", faixa de leitura parcial; Imagem 2: busto de perfil, estrelas, legenda circular e \"1913\"; desgaste e manchas visíveis nas duas imagens."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Metal"]
  evidence: ["Imagens 1 e 2: disco de metal de cor prateada com motivos e letras em relevo."]
- inbcm-conservacao: "Regular"
  evidence: "Imagens 1 e 2: desgaste do relevo, riscos e manchas escuras (Imagem 2, lado esquerdo), bordas levemente irregulares, sem perdas que comprometam a leitura."
- inbcm-localprod: null
- inbcm-dataprod: "1913"
  evidence: "Imagem 2: \"1913\" em relevo na parte inferior."
- inbcm-classificacao: "Dinheiro"
  evidence: "Tesauro: classe de Moeda"
  tesauro: names=["Moeda","Moeda de 2000 réis com inscrição Ordem e Progresso"] -> ["Moeda (exact 1)"]

## m3: 3 views (claude-sonnet-5-5; in 10629, out 2874, thinking 1986, stop; system prompt 0 chars; images 1568x1043 + 1568x1043 + 1568x1043; 31.1 s)
**Denominação** ✓ (expected "Carimbo", or Carimbo (selo)) — **Classificação** ✓ 

- inbcm-denominacao: "Carimbo"
  evidence: "Tesauro (exact: \"Carimbo\"): Equipamento de comunicação › Equipamento de comunicação escrita › Carimbo. Alternativas: Carimbo (selo) — Equipamento de comunicação › Equipamento de comunicação escrita › Carimbo › Carimbo (selo); Carimbo (selo) — Equipamento de comunicação › Equipamento de comunicação escrita › Matriz de selo › Carimbo (selo)"
- titulo-2: "Carimbo de cabo preto com matriz circular gravada"
  evidence: "Imagem 1: cabo preto sobre base de madeira avermelhada; Imagem 2: matriz circular metálica com texto em relevo."
- inbcm-autor: ["J. XAVIER"]
  evidence: ["Imagem 3: marca em baixo-relevo na lateral da base de madeira: \"J. XAVIER \/ RUA SACHET 18\"."]
- descricao-2: "Carimbo com cabo preto de madeira aparentemente torneada, em formato de pomo alongado e acabamento brilhante. A base é quadrada, de madeira com acabamento marrom-avermelhado. Na face inferior, há matriz circular de metal com texto em relevo e escrita espelhada. Apresenta manchas e oxidação na matriz, trinca visível no metal, pequenas perdas no acabamento do cabo e sujidade na base.\nInscrições: em relevo, na matriz circular da face inferior, em escrita espelhada: \"CABANGU[?] \/ S=D \/ MANTIQUEIRA\"; marcado em baixo-relevo, em uma lateral da base: \"J. XAVIER \/ RUA SACHET 18\"."
  evidence: "Imagem 1: cabo e base; Imagem 2: matriz circular com texto espelhado, manchas e trinca; Imagem 3: marca na lateral da base."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Madeira","Metal","Gravação"]
  evidence: ["Imagens 1 e 3: cabo e base com aparência de madeira, veios visíveis na base.","Imagem 2: matriz circular de aparência metálica.","Imagens 2 e 3: texto em relevo na matriz e marca impressa na lateral da base."]
- inbcm-conservacao: "Regular"
  evidence: "Imagem 2: manchas, oxidação e trinca visível na matriz metálica; Imagem 1: pequenas perdas no acabamento do cabo; Imagem 3: sujidade na lateral da base. Objeto íntegro."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Equipamento de comunicação escrita"
  evidence: "Tesauro: classe de Carimbo"
  tesauro: names=["Carimbo","Carimbo de cabo preto com matriz circular gravada"] -> ["Carimbo (exact 1)","Carimbo (selo) (exact 0.98)","Carimbo (selo) (exact 0.98)"]

## m4: 2 views (claude-sonnet-5-5; in 9158, out 2492, thinking 1665, stop; system prompt 0 chars; images 1568x1262 + 1568x1176; 26.3 s)
**Denominação** ✓ (expected "Prato", or Prato decorativo) — **Classificação** ✓ 

- inbcm-denominacao: "Prato"
  evidence: "Tesauro (exact: \"Prato\"): Objetos domésticos › Objetos e equipamentos de serviço de alimentos › Prato. Alternativas: Prato (música) — Objetos de atividades artísticas › Objetos associados à música › Instrumento musical › Membranofone › Bateria › Prato (música); Prato (música) — Objetos de atividades artísticas › Objetos associados à música › Instrumento musical › Idiofone › Prato (música)"
- titulo-2: "Prato de porcelana com decoração floral pintada"
  evidence: "Imagem 1: guirlanda de rosas e margaridas pintadas; porcelana branca aparente com filete azul na borda."
- inbcm-autor: ["Z.S.&C°"]
  evidence: ["Imagem 2: marca em verde na área central da parte inferior do prato, linha superior: \"Z.S.&C°\"."]
- descricao-2: "Prato circular e raso, de porcelana aparentemente branca, com filete azul na borda. Decoração aparentemente pintada à mão: guirlanda de quatro rosas rosa-magenta alternadas com margaridas azuis, laranja e amarelas e folhas verdes, deixando o centro livre. Há pequenas perdas ou manchas escuras na borda.\n\nInscrições: marca carimbada em verde, na área central da parte inferior: \"Z.S.&C° \/ BAVARIA[?]\"."
  evidence: "Imagem 1: decoração floral, filete azul e pequenas perdas na borda (alto à direita e embaixo à esquerda). Imagem 2: marca verde \"Z.S.&C° \/ BAVAR...\" com final parcialmente ilegível; etiqueta de inventário não transcrita."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Porcelana","Pintura"]
  evidence: ["Imagem 1 e 2: corpo branco liso e brilhante, aparentemente de porcelana.","Imagem 1: flores e folhas com pinceladas visíveis, aparentemente pintadas à mão."]
- inbcm-conservacao: "Regular"
  evidence: "Imagem 1: pequenas perdas ou manchas escuras na borda, no alto à direita e embaixo à esquerda; sem fraturas."
- inbcm-localprod: "Bavaria"
  evidence: "Imagem 2: marca em verde na parte inferior com \"BAVAR...\"; letras finais parcialmente ilegíveis."
- inbcm-dataprod: null
- inbcm-classificacao: "Objetos e equipamentos de serviço de alimentos"
  evidence: "Tesauro: classe de Prato"
  tesauro: names=["Prato","Prato de porcelana com decoração floral pintada"] -> ["Prato (exact 1)","Prato (música) (exact 0.98)","Prato (música) (exact 0.98)"]

