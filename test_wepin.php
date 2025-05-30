<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>Wepin 지갑 연동 테스트</title>
  <!-- Wepin SDK: defer 제거 -->
  <script src="https://cdn.jsdelivr.net/npm/@wepin/widget-sdk@0.0.2-alpha/dist/wepin-widget-sdk.js"></script>
</head>
<body>
  <h2>베리챗코인 안전거래 테스트</h2>
  <p>거래하기 버튼을 누르면 운영자 지갑으로 베리코인 100개가 이체됩니다.</p>
  
  <button id="loginBtn">지갑 로그인</button>
  <button id="tradeBtn" disabled>거래하기 (100 BERRY 이체)</button>

  <script>
    let userWallet = null;
    let wepinInitialized = false;

    document.getElementById('loginBtn').addEventListener('click', async () => {
      try {
        if (!wepinInitialized) {
          await window.Wepin.init(
            '82c1a61d6e45f0c580aed1fa2c422287', // App ID
            'ak_live_M3OBCVipGupiZWOhLbQSLEDB72be3dzBg5nvscrmHjV', // API Key
            {
              type: 'popup',
              defaultLanguage: 'ko',
              defaultCurrency: 'KRW',
            }
          );
          wepinInitialized = true;
        }

        const result = await window.Wepin.connect();
        userWallet = result?.walletAddress || null;
        if (userWallet) {
          alert("지갑 로그인 성공: " + userWallet);
          document.getElementById('tradeBtn').disabled = false;
        } else {
          alert("지갑 연동 실패");
        }

      } catch (e) {
        console.error("Wepin 오류 (로그인):", e);
        alert("Wepin 지갑 연동 실패");
      }
    });

    document.getElementById('tradeBtn').addEventListener('click', async () => {
      if (!userWallet) return alert("지갑 먼저 로그인하세요");

      try {
        const tx = await window.Wepin.sendToken({
          to: '0x5Dd6593237eA26C012a9e41bb4D71018644C62b2', // 운영자 지갑 주소
          amount: '100',
          token: 'BERRY'
        });

        console.log("거래 결과:", tx);
        if (tx?.txHash) {
          alert("성공적으로 100 BERRY 전송 완료! TX: " + tx.txHash);
        } else {
          alert("이체 실패 또는 취소됨");
        }

      } catch (e) {
        console.error("Wepin 오류 (거래):", e);
        alert("거래 중 오류 발생");
      }
    });
  </script>
</body>
</html>
